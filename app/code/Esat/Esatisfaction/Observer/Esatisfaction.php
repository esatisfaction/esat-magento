<?php

namespace Esat\Esatisfaction\Observer;

use Esat\Esatisfaction\Helper\Data;
use Esat\Esatisfaction\Model\ItemFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Esatisfaction
 * @package Esat\Esatisfaction\Observer
 */
class Esatisfaction implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var ItemFactory
     */
    protected $_itemFactory;

    /**
     * Esatisfaction constructor.
     *
     * @param Data        $helper
     * @param ItemFactory $itemFactory
     */
    public function __construct(Data $helper, ItemFactory $itemFactory)
    {
        $this->helper = $helper;
        $this->_itemFactory = $itemFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        // Get status
        $status = $this->helper->getStatus();
        if (!$status) {
            return;
        }

        // Get data
        $token = $this->helper->getToken();
        $applicationId = $this->helper->getApplicationId();
        $auto = $this->helper->getAuto();

        // Check for auto config
        if ($auto) {
            return;
        }

        // Check for token and application id
        if (empty($token) || empty($applicationId)) {
            return;
        }

        // Get order data
        $order = $observer->getEvent()->getOrder();
        $status = $order->getStatus();
        $shippingMethod = explode('_', $order->getShippingMethod());

        // Get responder data
        $email = $order->getCustomerEmail();
        $telephone = $order->getBillingAddress()->getTelephone();

        // Check if shipping_method is for pickup or delivery
        $pickupMethods = $this->helper->getPickUpShippings();

        if (in_array($shippingMethod[0], $pickupMethods)) {
            $isPickup = true;
            $sendQuestionnaireStatus = explode(',', $this->helper->getPickupSendQuestionnaire());
            $cancelQuestionnaireStatus = explode(',', $this->helper->getPickupCancelQuestionnaire());
            $questionnaireId = $this->helper->getPickupQuestionnaireId();
            $pipelineId = $this->helper->getPickupPipelineId();
        } else {
            $isPickup = false;
            $sendQuestionnaireStatus = explode(',', $this->helper->getDeliverySendQuestionnaire());
            $cancelQuestionnaireStatus = explode(',', $this->helper->getDeliveryCancelQuestionnaire());
            $questionnaireId = $this->helper->getDeliveryQuestionnaireId();
            $pipelineId = $this->helper->getDeliveryPipelineId();
        }

        /* Status for sending questionnaire */
        if (in_array($status, $sendQuestionnaireStatus)) {
            $url = sprintf('https://api.e-satisfaction.com/v3.0/q/questionnaire/%s/pipeline/%s/queue/item', $questionnaireId, $pipelineId);
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

            // Check for pickup
            if ($isPickup === false) {
                $daysAfter = $this->helper->getDeliveryDaysAfter();
                $currentDate = date('Y-m-d');
                $currentTime = strtotime($currentDate . ' + ' . $daysAfter . ' days ');
                $postFields['send_time'] = date('Y-m-d H:m:s', $currentTime);
            }

            // Prepare curl
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
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $response = json_decode($response, true);

            if ($httpCode == 201) {
                $item = $this->_itemFactory->create();
                $item->setOrderId($order->getId());
                $item->setItemId($response['item_id']);
                $item->save();
            }
        }

        /* Status for canceling questionnaire */
        if (in_array($status, $cancelQuestionnaireStatus)) {
            $collection = $this->_itemFactory->create()->getCollection()->addFieldToFilter('order_id', $order->getId());
            $item = $collection->getFirstItem();
            $url = sprintf('https://api.e-satisfaction.com/v3.0/q/queue/item/%s', $item->getItemId());

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'esat-auth:' . $token,
            ]);

            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 204) {
                $item->delete();
            }
        }
    }
}
