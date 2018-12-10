<?php
/*
 * A Helper Data file is mandatory in order to create a module
 */
class Esat_Esatisfaction_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getApplicationId()
    {
        return Mage::getStoreConfig('esatisfaction/application/application_id');
    }

    public function getAuto()
    {
        return Mage::getStoreConfig('esatisfaction/application/auto');
    }

    public function getCheckoutQuestionnaireId()
    {
        return Mage::getStoreConfig('esatisfaction/checkout/questionnaire_id');
    }

    public function getDeliveryQuestionnaireId()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/questionnaire_id');
    }

    public function getDeliveryPipelineId()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/pipeline_id');
    }

    public function getDeliveryDaysAfter()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/days_after');
    }

    public function getDeliverySendQuestionnaire()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/send_questionnaire');
    }

    public function getDeliveryCancelQuestionnaire()
    {
        return Mage::getStoreConfig('esatisfaction/delivery/cancel_questionnaire');
    }

    public function getPickupQuestionnaireId()
    {
        return Mage::getStoreConfig('esatisfaction/pickup/questionnaire_id');
    }

    public function getPickupPipelineId()
    {
        return Mage::getStoreConfig('esatisfaction/pickup/pipeline_id');
    }

    public function getPickupSendQuestionnaire()
    {
        return Mage::getStoreConfig('esatisfaction/pickup/send_questionnaire');
    }

    public function getPickupCancelQuestionnaire()
    {
        return Mage::getStoreConfig('esatisfaction/pickup/cancel_questionnaire');
    }

    public function getToken()
    {
        return Mage::getStoreConfig('esatisfaction/authentication/token');
    }

    public function getIsJQueryEnabled()
    {
        return Mage::getStoreConfig('esatisfaction/setup/jquery_installed');
    }

    public function getPickUpShippings()
    {
        $pickup_methods = Mage::getStoreConfig('esatisfaction/pickup/store_pickup');

        return explode(',', $pickup_methods);
    }
}