<?php

/**
 * Class Esat_Esatisfaction_Model_Observer
 */
class Esat_Esatisfaction_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function check_order_status_change(Varien_Event_Observer $observer)
    {
        $helperData = Mage::helper('esatisfaction/data');
        $status = $helperData->getStatus();

        // Check for status
        if (!$status) {
            return;
        }

        // Get token
        $token = $helperData->getToken();
        $domain = $helperData->getWorkingDomain();
        $customFlows = $helperData->getCustomFlows();
        if (!$customFlows || empty($token)) {
            // Module is marked for auto-flow from dashboard
            return;
        }

        // Get order status
        $order = $observer->getOrder();
        $status = $order->getStatus();
        $shipping_method = explode('_', $order->getShippingMethod());

        /**
         * Get data from user.
         *
         * Based on testing, we receive user email and telephone
         * using the following functions:
         */
        $email = $order->getCustomerEmail();
        $telephone = $order->getBillingAddress()->getTelephone();

        // Check if shipping_method is for pickup or delivery
        $pickupMethods = $helperData->getPickUpShippingMethods();
        if (in_array($shipping_method[0], $pickupMethods)) {
            $isPickup = true;
            $sendQuestionnaireStatus = explode(',', $helperData->getPickupSendQuestionnaire());
            $cancelQuestionnaireStatus = explode(',', $helperData->getPickupCancelQuestionnaire());
            $questionnaireId = $helperData->getPickupQuestionnaireId();
            $pipelineId = $helperData->getPickupPipelineId();
        } else {
            $isPickup = false;
            $sendQuestionnaireStatus = explode(',', $helperData->getDeliverySendQuestionnaire());
            $cancelQuestionnaireStatus = explode(',', $helperData->getDeliveryCancelQuestionnaire());
            $questionnaireId = $helperData->getDeliveryQuestionnaireId();
            $pipelineId = $helperData->getDeliveryPipelineId();
        }

        // Status for sending questionnaire
        if (in_array($status, $sendQuestionnaireStatus)) {
            $url = sprintf('https://api.e-satisfaction.com/v3.2/q/questionnaire/%s/pipeline/%s/queue/item', $questionnaireId, $pipelineId);
            $postFields = [
                'responder_channel_identifier' => $email,
                'locale' => 'el',
                'metadata' => [
                    'questionnaire' => [
                        'transaction_id' => $order->getIncrementId(),
                        'transaction_date' => $order->getCreatedAt(),
                    ],
                    'responder' => [
                        'email' => $email,
                        'phone_number' => $telephone,
                    ],
                ],
            ];

            if ($isPickup === false) {
                $daysAfter = $helperData->getDeliveryDaysAfter();
                $currentDate = date('Y-m-d');
                $currentTime = strtotime($currentDate . ' + ' . $daysAfter . ' days ');
                $postFields['send_time'] = date('Y-m-d H:m:s', $currentTime);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'esat-auth:' . $token,
                'esat-domain:' . $domain,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $response = json_decode($response, true);
            if ($httpCode == 201) {
                $item = Mage::getModel('esatisfaction/item');
                $item->setOrderId($order->getIncrementId());
                $item->setItemId($response['item_id']);
                $item->save();
            }
        }

        // Status for canceling questionnaire
        if (in_array($status, $cancelQuestionnaireStatus)) {
            $collection = Mage::getModel('esatisfaction/item')->getCollection()->addFieldToFilter('order_id', $order->getIncrementId());
            $item = $collection->getFirstItem();

            $url = 'https://api.e-satisfaction.com/v3.2/q/queue/item/' . $item->getItemId();
            // Set queue item as CANCELLED/ABORTED
            $postFields = [
                'status_id' => 5,
                'result' => 'Order cancelled from Magento Admin',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'esat-auth:' . $token,
                'esat-domain:' . $domain,
            ]);

            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Delete local item on success
            if ($httpCode == 200) {
                $item->delete();
            }
        }
    }
}
