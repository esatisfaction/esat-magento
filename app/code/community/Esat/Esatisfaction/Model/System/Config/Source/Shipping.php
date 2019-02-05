<?php

/**
 * Class Esat_Esatisfaction_Model_System_Config_Source_Shipping
 */
class Esat_Esatisfaction_Model_System_Config_Source_Shipping
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $themes = [];
        $methods = Mage::getSingleton('shipping/config')->getActiveCarriers();
        foreach ($methods as $code => $method) {
            $themes[] = [
                'value' => $code,
                'label' => Mage::getStoreConfig('carriers/' . $code . '/title'),
            ];
        }

        return $themes;
    }
}
