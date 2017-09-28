<?php
class Esat_Esatisfaction_Block_Checkoutsuccessscripts
    extends Mage_Core_Block_Template
{
	
	public function isCheckoutQuestionaireEnabled(){
		return Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_checkout_questionnaires');
	}
	
	public function isAfterSalesSurveysEnabled(){
		return Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_after_sales_surveys');
	}
	
	public function getSiteId(){
		return Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_id');
	}
	
	public function getPublicKey(){
		return Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_public_key');
	}
	
	public function getPrivateKey(){
		return Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_private_key');
	}
	
    public function getToken()
    {
		$auth_key = Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_authentication_key'); 
		$token = file_get_contents("https://www.e-satisfaction.gr/miniquestionnaire/genkey.php?site_auth=".$auth_key); 
        return $token;
    }
	
    public function getOrderId()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        return $orderId;
    }
	
    public function getIncrementId()
    {
		$order = Mage::getModel('sales/order');
		$order->load(Mage::getSingleton('checkout/session')->getLastOrderId());
		$increment_id =  $order->getIncrementId();
        return $increment_id;
    }
	
	public function getCustomerEmail(){
		$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($orderId);
		return $order->getCustomerEmail();
	}
}