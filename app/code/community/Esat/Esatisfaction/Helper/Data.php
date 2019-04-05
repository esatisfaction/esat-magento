<?php

/**
 * Class Esat_Esatisfaction_Helper_Data
 * A Helper Data file is mandatory in order to create a module
 */
class Esat_Esatisfaction_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return string
     */
    public function getStatus()
    {
        return Mage::getStoreConfig('esatisfaction/application/status') ?: false;
    }

    /**
     * @return string
     */
    public function getApplicationId()
    {
        return Mage::getStoreConfig('esatisfaction/application/application_id') ?: '';
    }

    /**
     * @return string
     */
    public function isJQueryEnabled()
    {
        return Mage::getStoreConfig('esatisfaction/application/jquery_installed') ?: false;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return Mage::getStoreConfig('esatisfaction/application/token') ?: '';
    }

    /**
     * @return string
     */
    public function getCheckoutQuestionnaireId()
    {
        return Mage::getStoreConfig('esatisfaction/checkout/questionnaire_id') ?: '';
    }

    /**
     * @return string
     */
    public function getCustomFlows()
    {
        return Mage::getStoreConfig('esatisfaction/checkout/custom_flows') ?: false;
    }

    /**
     * @return string
     */
    public function getDeliveryQuestionnaireId()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/questionnaire_id') ?: '';
    }

    /**
     * @return string
     */
    public function getDeliveryPipelineId()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/pipeline_id') ?: '';
    }

    /**
     * @return string
     */
    public function getDeliveryDaysAfter()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/days_after') ?: '';
    }

    /**
     * @return string
     */
    public function getDeliverySendQuestionnaire()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/send_questionnaire') ?: '';
    }

    /**
     * @return string
     */
    public function getDeliveryCancelQuestionnaire()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/cancel_questionnaire') ?: '';
    }

    /**
     * @return string
     */
    public function getPickupQuestionnaireId()
    {
        return Mage::getStoreConfig('esatisfaction/pickup/questionnaire_id') ?: '';
    }

    /**
     * @return string
     */
    public function getPickupPipelineId()
    {
        return Mage::getStoreConfig('esatisfaction/pickup/pipeline_id') ?: '';
    }

    /**
     * @return string
     */
    public function getPickupSendQuestionnaire()
    {
        return Mage::getStoreConfig('esatisfaction/pickup/send_questionnaire') ?: '';
    }

    /**
     * @return string
     */
    public function getPickupCancelQuestionnaire()
    {
        return Mage::getStoreConfig('esatisfaction/pickup/cancel_questionnaire') ?: '';
    }

    /**
     * @return array
     */
    public function getPickUpShippingMethods()
    {
        $pickup_methods = Mage::getStoreConfig('esatisfaction/pickup/store_pickup') ?: '';

        return explode(',', $pickup_methods);
    }
}
