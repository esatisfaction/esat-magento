<?php

class Esat_Esatisfaction_Model_System_Config_Source_Order_Status
{
    public function toOptionArray()
    {
        $order_statuses = Mage::getModel('sales/order_status')->getResourceCollection()->getData();

        $statuses = [];
        foreach ($order_statuses as $order_status) {
            $statuses[] = [
                'value' => $order_status['status'],
                'label' => $order_status['label']
            ];
        }

        return $statuses;
    }
}