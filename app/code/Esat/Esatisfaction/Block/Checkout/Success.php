<?php

namespace Esat\Esatisfaction\Block\Checkout;

use Esat\Esatisfaction\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class Success extends Template
{
    protected $_checkoutSession;
    protected $orderRepository;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Data $helper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository)
    {
        $this->_checkoutSession = $checkoutSession;
        $this->_orderRepository = $orderRepository;
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    public function _getApplicationId()
    {
        return $this->_helper->getApplicationId();
    }

    public function _getCheckoutQuestionnaireId()
    {
        return $this->_helper->getCheckoutQuestionnaireId();
    }

    public function _getStatus()
    {
        return $this->_helper->getStatus();
    }

    /*
     * Get last orders data needed
     */
    public function getOrderData()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $_order_id = $this->_checkoutSession->getLastOrderId();
        $_order_data = $objectManager->create('\Magento\Sales\Model\Order')->load($_order_id);

        $email = $_order_data->getCustomerEmail();

        $telephone = $_order_data->getBillingAddress()->getTelephone();

        $shipping_method = explode('_', $_order_data->getShippingMethod());
        $pick_up_methods = $this->_helper->getPickUpShippings();

        if (in_array($shipping_method[0], $pick_up_methods)) {
            $store_pickup = 'true';
        } else {
            $store_pickup = 'false';
        }

        return [
            'email'			     => $email,
            'telephone'		  => $telephone,
            'increment_id'	=> $_order_data->getIncrementId(),
            'created_at'	  => $_order_data->getCreatedAt(),
            'store_pickup'	=> $store_pickup,
        ];
    }
}
