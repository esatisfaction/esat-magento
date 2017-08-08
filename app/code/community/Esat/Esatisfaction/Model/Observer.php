<?php

class Esat_Esatisfaction_Model_Observer
{
    public function esatisfactionGridAddColumn(Varien_Event_Observer $observer)
    {
        if (!$observer->getBlock()) {
            return null;
        }
		
		if ($this->isSalesOrderGrid($observer->getBlock())) {
			 
			 
			 $observer->getBlock()->addColumnAfter(
				'sales_order_smiley',
				array(
					'header'   => Mage::helper('esatisfaction')->__('e-satisfaction'),
					'index'    => 'sales_order_smiley',
					'width'    => 110,
					'filter'   => false,
					'sortable' => false,
					'renderer' => 'esatisfaction/adminhtml_grid_renderer_sales_order_smiley',
				),
				'real_order_id'
			);
			
        }
    }
	
	public function isSalesOrderGrid($block){
		
		if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {
            return true;
        } 
		if ($block instanceof Mage_Adminhtml_Block_Customer_Edit_Tab_View_Orders) {
            return true;
        } 
		if ($block instanceof Mage_Adminhtml_Block_Customer_Edit_Tab_Orders) {
            return true;
        } 
		return false;
    }
	
    public function salesOrderCollectionAddSmiley(Varien_Event_Observer $observer)
    {
		if ($this->isSalesOrderGrid($observer->getBlock())) {
			$observer->getCollection()->addAttributeToSelect('sales_order_smiley');
		}
    }
	
	public function updateAdminMenu(){
		
		$site_id = Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_id');
		
		$url = 'https://www.e-satisfaction.gr/api/v2/prestashop/custom_question_section/'.$site_id;

		
		$public_key 	= Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_public_key');
		$private_key 	= Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_private_key');
		$timestamp 		= round(microtime(true), 3);
		$method			= 'GET';
		
		$hash = hash_hmac('sha256', $public_key.$timestamp.$method, $private_key, true);
		$hashInBase64 = base64_encode($hash);
		
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-HASH: '.$hashInBase64,
			'X-Public: '.$public_key,
			'X-Microtime: '.$timestamp
		));
		
		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);
		
		$answers = json_decode(utf8_encode($result), true);
		
		if(isset($answers['error'])){
			Mage::getConfig()->saveConfig('esatisfaction/section_one/esatisfaction_custom_questions', '0', 'default', 0);
		}else{
			Mage::getConfig()->saveConfig('esatisfaction/section_one/esatisfaction_custom_questions', '1', 'default', 0);
		}
				
		
		
	}
	
	public function cancelAfterSales(Varien_Event_Observer $observer){
		$site_id = Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_id');
		
		$payment = $observer->getEvent()->getPayment();
		$order = $payment->getOrder();
		$order_Id = $order->getId();
		
		$url = 'https://www.e-satisfaction.gr/api/v2/prestashop/delete_aftersales_mail/'.$site_id.'?order_id='.$order_id;

		
		$public_key 	= Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_public_key');
		$private_key 	= Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_private_key');
		$timestamp 		= round(microtime(true), 3);
		$method			= 'GET';
		
		$hash = hash_hmac('sha256', $public_key.$timestamp.$method, $private_key, true);
		$hashInBase64 = base64_encode($hash);
		
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-HASH: '.$hashInBase64,
			'X-Public: '.$public_key,
			'X-Microtime: '.$timestamp
		));
		
		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);
		
	}
	
	public function sendSettingsToEsatisfaction(Varien_Event_Observer $observer){		
		
		$site_id = Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_id');
		$config_pg_browse = Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_browse_questions');
		$config_pg_checkout = Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_checkout_questionnaires');
		$config_pg_aftersales = Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_after_sales_surveys');
		
		$url = 'https://www.e-satisfaction.gr/api/v2/prestashop/module_settings/'.$site_id.'?config_pg_browse='.$config_pg_browse.'&config_pg_checkout='.$config_pg_checkout.'&config_pg_aftersales='.$config_pg_aftersales;

		$public_key 	= Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_public_key');
		$private_key 	= Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_private_key');
		$timestamp 		= round(microtime(true), 3);
		$method			= 'GET';
		
		$hash = hash_hmac('sha256', $public_key.$timestamp.$method, $private_key, true);
		$hashInBase64 = base64_encode($hash);
		
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-HASH: '.$hashInBase64,
			'X-Public: '.$public_key,
			'X-Microtime: '.$timestamp
		));
		
		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);
	}

}