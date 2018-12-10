<?php

class Esat_Esatisfaction_Model_System_Config_Source_Shipping
{
    public function toOptionArray()
    {
        $methods = Mage::getSingleton('shipping/config')->getActiveCarriers();

        $themes = [];
        foreach ($methods as $code => $method) {
            $themes[] = [
                'value' => $code,
                'label' => Mage::getStoreConfig('carriers/'.$code.'/title')
            ];
        }

        return $themes;
    }
}