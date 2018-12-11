<?php

class Esat_Esatisfaction_Model_Observer
{
    public function check_order_status_change(Varien_Event_Observer $observer)
    {
        $esat_helper = Mage::helper('esatisfaction/data');

        $token = $esat_helper->getToken();
        $application_id = $esat_helper->getApplicationId();
        $auto = $esat_helper->getAuto();

        if ($auto) {
            $order = $observer->getOrder();
            $status = $order->getStatus();
            $shipping_method = explode('_', $order->getShippingMethod());

            if (!empty($token) && !empty($token)) {
                if ($order->getCustomerIsGuest()) {
                    $email = $order->getCustomerEmail();
                    $telephone = $order->getBillingAddress()->getTelephone();
                } else {
                    $email = $order->getEmail();
                    $telephone = $order->getTelephone();
                }

                /* Check if shipping_method is for pickup or delivery */
                $pickup_methods = $esat_helper->getPickUpShippings();
                if (in_array($shipping_method[0], $pickup_methods)) {
                    $is_pickup = true;
                    $send_questionnaire_status = explode(',', $esat_helper->getPickupSendQuestionnaire());
                    $cancel_questionnaire_status = explode(',', $esat_helper->getPickupCancelQuestionnaire());
                    $questionnaire_id = $esat_helper->getPickupQuestionnaireId();
                    $pipeline_id = $esat_helper->getPickupPipelineId();
                } else {
                    $is_pickup = false;
                    $send_questionnaire_status = explode(',', $esat_helper->getDeliverySendQuestionnaire());
                    $cancel_questionnaire_status = explode(',', $esat_helper->getDeliveryCancelQuestionnaire());
                    $questionnaire_id = $esat_helper->getDeliveryQuestionnaireId();
                    $pipeline_id = $esat_helper->getDeliveryPipelineId();
                }

                /* Status for sending questionnaire */
                if (in_array($status, $send_questionnaire_status)) {
                    $url = 'https://api.e-satisfaction.com/v3.0/q/questionnaire/';
                    $url .= $questionnaire_id;
                    $url .= '/pipeline/';
                    $url .= $pipeline_id;
                    $url .= '/queue/item';

                    $post_fields = [
                        'responder_channel_identifier' => $email,
                        'locale'                       => 'el',
                        'metadata'                     => [
                            'questionnaire'    => [
                                'transaction_id'   => $order->getIncrementId(),
                                'transaction_date'     => $order->getCreatedAt(),
                            ],
                            'responder'    => [
                                'email'            => $email,
                                'phone_number' => $telephone
                            ]
                        ]
                    ];

                    if ($is_pickup === false) {
                        $days_after = $esat_helper->getDeliveryDaysAfter();
                        $cur_date = date('Y-m-d');
                        $cur_time = strtotime($cur_date.' + '.$days_after.' days ');
                        $post_fields['send_time'] = date('Y-m-d H:m:s', $cur_time);
                    }

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, false);

                    curl_setopt($ch, CURLOPT_POST, true);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));

                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                      'Content-Type: application/json',
                      'Accept: application/json',
                      'esat-auth:'.$token
                    ]);

                    $response = curl_exec($ch);
                    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    $response = json_decode($response, true);

                    if ($httpcode == 201) {
                        $item = Mage::getModel('esatisfaction/item');
                        $item->setOrderId($order->getIncrementId());
                        $item->setItemId($response['item_id']);
                        $item->save();
                    }
                }

                /* Status for canceling questionnaire */
                if (in_array($status, $cancel_questionnaire_status)) {
                    $collection = Mage::getModel('esatisfaction/item')->getCollection()->addFieldToFilter('order_id', $order->getIncrementId());
                    $item = $collection->getFirstItem();

                    $url = 'https://api.e-satisfaction.com/v3.0/q/queue/item/'.$item->getItemId();

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                      'Content-Type: application/json',
                      'Accept: application/json',
                      'esat-auth:'.$token
                    ]);

                    $response = curl_exec($ch);
                    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($httpcode == 204) {
                        $item->delete();
                    }
                }
            }
        }
    }
}