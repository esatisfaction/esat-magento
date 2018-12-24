<?php

namespace Esat\Esatisfaction\Observer;

use Esat\Esatisfaction\Helper\Data;
use Magento\Framework\Event\ObserverInterface;

class Esatisfaction implements ObserverInterface
{
    protected $helper;
    protected $_itemFactory;

    public function __construct(
        Data $helper,
        \Esat\Esatisfaction\Model\ItemFactory $itemFactory
    ) {
        $this->helper = $helper;
        $this->_itemFactory = $itemFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $status = $this->helper->getStatus();
        if ($status) {
            $token = $this->helper->getToken();
            $application_id = $this->helper->getApplicationId();
            $auto = $this->helper->getAuto();

            if (!$auto) {
                $order = $observer->getEvent()->getOrder();
                $status = $order->getStatus();
                $shipping_method = explode('_', $order->getShippingMethod());

                if (!empty($token) && !empty($application_id)) {
                    $email = $order->getCustomerEmail();
                    $telephone = $order->getBillingAddress()->getTelephone();

                    /* Check if shipping_method is for pickup or delivery */
                    $pickup_methods = $this->helper->getPickUpShippings();

                    if (in_array($shipping_method[0], $pickup_methods)) {
                        $is_pickup = true;
                        $send_questionnaire_status = explode(',', $this->helper->getPickupSendQuestionnaire());
                        $cancel_questionnaire_status = explode(',', $this->helper->getPickupCancelQuestionnaire());
                        $questionnaire_id = $this->helper->getPickupQuestionnaireId();
                        $pipeline_id = $this->helper->getPickupPipelineId();
                    } else {
                        $is_pickup = false;
                        $send_questionnaire_status = explode(',', $this->helper->getDeliverySendQuestionnaire());
                        $cancel_questionnaire_status = explode(',', $this->helper->getDeliveryCancelQuestionnaire());
                        $questionnaire_id = $this->helper->getDeliveryQuestionnaireId();
                        $pipeline_id = $this->helper->getDeliveryPipelineId();
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
                                    'transaction_id'       => $order->getIncrementId(),
                                    'transaction_date'     => $order->getCreatedAt(),
                                ],
                                'responder'    => [
                                    'email'            => $email,
                                    'phone_number'     => $telephone,
                                ],
                            ],
                        ];

                        if ($is_pickup === false) {
                            $days_after = $this->helper->getDeliveryDaysAfter();
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
                          'esat-auth:'.$token,
                        ]);

                        $response = curl_exec($ch);
                        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);

                        $response = json_decode($response, true);

                        if ($httpcode == 201) {
                            $item = $this->_itemFactory->create();
                            $item->setOrderId($order->getId());
                            $item->setItemId($response['item_id']);
                            $item->save();
                        }
                    }

                    /* Status for canceling questionnaire */
                    if (in_array($status, $cancel_questionnaire_status)) {
                        $collection = $this->_itemFactory->create()->getCollection()->addFieldToFilter('order_id', $order->getId());
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
                          'esat-auth:'.$token,
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
}
