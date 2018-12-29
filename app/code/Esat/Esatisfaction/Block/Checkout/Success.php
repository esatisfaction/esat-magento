<?php

namespace Esat\Esatisfaction\Block\Checkout;

use Esat\Esatisfaction\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Success
 * @package Esat\Esatisfaction\Block\Checkout
 */
class Success extends Template
{
    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var
     */
    protected $orderRepository;

    /**
     * Success constructor.
     *
     * @param Context                  $context
     * @param StoreManagerInterface    $storeManager
     * @param Data                     $helper
     * @param Session                  $checkoutSession
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Data $helper,
        Session $checkoutSession,
        OrderRepositoryInterface $orderRepository)
    {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderRepository = $orderRepository;
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

    /**
     * Get last orders data needed
     *
     * @return array
     */
    public function getOrderData()
    {
        $objectManager = ObjectManager::getInstance();

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
            'email' => $email,
            'telephone' => $telephone,
            'increment_id' => $_order_data->getIncrementId(),
            'created_at' => $_order_data->getCreatedAt(),
            'store_pickup' => $store_pickup,
        ];
    }
}
