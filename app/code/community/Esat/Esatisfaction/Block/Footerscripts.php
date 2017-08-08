<?php
class Esat_Esatisfaction_Block_Footerscripts
    extends Mage_Core_Block_Template
{
	public function isBrowseQuestionsEnabled(){
		return Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_browse_questions');
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
}