<?php

namespace Esat\Esatisfaction\Block;

use Esat\Esatisfaction\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class Head extends Template
{
    public function __construct(Context $context, StoreManagerInterface $storeManager, Data $helper)
    {
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    public function _getApplicationId()
    {
        return $this->_helper->getApplicationId();
    }

    public function _getStatus()
    {
        return $this->_helper->getStatus();
    }
}
