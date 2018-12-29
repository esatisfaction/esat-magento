<?php

namespace Esat\Esatisfaction\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Option\ArrayInterface;
use Magento\Shipping\Model\Config;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Shipping
 * @package Esat\Esatisfaction\Model\Config\Source
 */
class Shipping implements ArrayInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Config
     */
    protected $shippingConfig;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Shipping constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Config                $shippingConfig
     * @param ScopeConfigInterface  $scopeConfig
     */
    public function __construct(StoreManagerInterface $storeManager, Config $shippingConfig, ScopeConfigInterface $scopeConfig)
    {
        $this->storeManager = $storeManager;
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $shippingMethodsArray = [];
        $allCarriers = $this->shippingConfig->getAllCarriers($this->storeManager->getStore());
        foreach ($allCarriers as $shippingCode => $shippingModel) {
            $shippingTitle = $this->scopeConfig->getValue('carriers/' . $shippingCode . '/title');
            $shippingMethodsArray[$shippingCode] = [
                'label' => $shippingTitle,
                'value' => $shippingCode,
            ];
        }

        return $shippingMethodsArray;
    }
}
