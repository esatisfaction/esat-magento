<?php

class Esat_Esatisfaction_Block_Checkout_Success extends Mage_Core_Block_Template
{
    /*
     * Get last orders data needed
     */
    public function getOrderData()
    {
        $_order_id = Mage::getSingleton('checkout/session')->getLastOrderId();
        $_order_data = Mage::getModel('sales/order')->load($_order_id);
        /*
         * If the customer checked out as a guest use bililng address telephone
         * else use the saved telephone
         */
        if ($_order_data->getCustomerIsGuest()) {
            $email = $_order_data->getCustomerEmail();
            $telephone = $_order_data->getBillingAddress()->getTelephone();
        } else {
            $email = $_order_data->getEmail();
            $telephone = $_order_data->getTelephone();
        }

        $shipping_method = explode('_', $_order_data->getShippingMethod());
        $pick_up_methods = Mage::helper('esatisfaction/data')->getPickUpShippings();
        if (in_array($shipping_method[0], $pick_up_methods)) {
            $store_pickup = 'true';
        } else {
            $store_pickup = 'false';
        }

        return [
            'email'        => $email,
            'telephone'    => $telephone,
            'increment_id' => $_order_data->getIncrementId(),
            'created_at'   => $_order_data->getCreatedAt(),
            'store_pickup' => $store_pickup,
        ];
    }
}
