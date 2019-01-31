<?php

/**
 * Class Esat_Esatisfaction_Block_Checkout_Success.
 */
class Esat_Esatisfaction_Block_Checkout_Success extends Mage_Core_Block_Template
{
    /**
     * Get last orders data needed.
     *
     * @return array
     */
    public function getOrderData()
    {
        // Get order data
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $orderData = Mage::getModel('sales/order')->load($orderId);

        /*
         * If the customer checked out as a guest use billing address telephone
         * else use the saved telephone
         */
        if ($orderData->getCustomerIsGuest()) {
            $email = $orderData->getCustomerEmail();
            $telephone = $orderData->getBillingAddress()->getTelephone();
        } else {
            $email = $orderData->getEmail();
            $telephone = $orderData->getTelephone();
        }

        // Define whether it is store pickup or not
        $shippingMethod = explode('_', $orderData->getShippingMethod());
        $pickUpMethods = Mage::helper('esatisfaction/data')->getPickUpShippings();
        if (in_array($shippingMethod[0], $pickUpMethods)) {
            $storePickup = 'true';
        } else {
            $storePickup = 'false';
        }

        return [
            'email'        => $email,
            'telephone'    => $telephone,
            'increment_id' => $orderData->getIncrementId(),
            'created_at'   => $orderData->getCreatedAt(),
            'store_pickup' => $storePickup,
        ];
    }
}
