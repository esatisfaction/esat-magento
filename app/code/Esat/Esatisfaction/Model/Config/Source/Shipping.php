<?php

namespace Esat\Esatisfaction\Model\Config\Source;

class Shipping implements \Magento\Framework\Option\ArrayInterface
{
    protected $storeManager;

    protected $shippingConfig;

    protected $scopeConfig;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Shipping\Model\Config $shippingConfig, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->storeManager = $storeManager;
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
    }

    public function toOptionArray()
    {
        $allCarriers = $this->shippingConfig->getAllCarriers($this->storeManager->getStore());

        $shippingMethodsArray = [];
        foreach ($allCarriers as $shippigCode => $shippingModel) {
            $shippingTitle = $this->scopeConfig->getValue('carriers/'.$shippigCode.'/title');
            $shippingMethodsArray[$shippigCode] = [
                'label' => $shippingTitle,
                'value' => $shippigCode,
            ];
        }

        return $shippingMethodsArray;
    }
}
