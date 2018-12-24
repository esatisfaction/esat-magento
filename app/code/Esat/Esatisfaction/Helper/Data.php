<?php

namespace Esat\Esatisfaction\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;

class Data extends AbstractHelper
{
    protected $encryptor;

    public function __construct(Context $context, EncryptorInterface $encryptor)
    {
        parent::__construct($context);
        $this->encryptor = $encryptor;
    }

    public function getApplicationId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/application/application_id', $scope);
    }

    public function getAuto($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/application/auto', $scope);
    }

    public function getStatus($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/application/status', $scope);
    }

    public function getCheckoutQuestionnaireId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/checkout/questionnaire_id', $scope);
    }

    public function getDeliveryQuestionnaireId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/delivery/questionnaire_id', $scope);
    }

    public function getDeliveryPipelineId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/delivery/pipeline_id', $scope);
    }

    public function getDeliveryDaysAfter($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/delivery/days_after', $scope);
    }

    public function getDeliverySendQuestionnaire($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/delivery/send_questionnaire', $scope);
    }

    public function getDeliveryCancelQuestionnaire($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/delivery/cancel_questionnaire', $scope);
    }

    public function getPickupQuestionnaireId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/pickup/questionnaire_id', $scope);
    }

    public function getPickupPipelineId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/pickup/pipeline_id', $scope);
    }

    public function getPickupSendQuestionnaire($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/pickup/send_questionnaire', $scope);
    }

    public function getPickupCancelQuestionnaire($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/pickup/cancel_questionnaire', $scope);
    }

    public function getToken($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        $token = $this->scopeConfig->getValue('esatisfaction/authentication/token', $scope);

        return $token;
    }

    public function getIsJQueryEnabled($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('esatisfaction/setup/jquery_installed', $scope);
    }

    public function getPickUpShippings($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        $pickup_methods = $this->scopeConfig->getValue('esatisfaction/pickup/store_pickup', $scope);

        return explode(',', $pickup_methods);
    }
}
