<?php

namespace Esat\Esatisfaction\Block;

use Esat\Esatisfaction\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Head
 * @package Esat\Esatisfaction\Block
 */
class Head extends Template
{
    /**
     * Head constructor.
     *
     * @param Context               $context
     * @param StoreManagerInterface $storeManager
     * @param Data                  $helper
     */
    public function __construct(Context $context, StoreManagerInterface $storeManager, Data $helper)
    {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
    }

    /**
     * @return string
     */
    public function _getApplicationId()
    {
        return $this->_helper->getApplicationId();
    }

    /**
     * @return bool
     */
    public function _getStatus()
    {
        return $this->_helper->getStatus();
    }

    /**
     * @return bool
     */
    public function _getIsJQueryEnabled()
    {
        return $this->_helper->getIsJQueryEnabled();
    }

}
