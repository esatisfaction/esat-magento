<?php
class Esat_Esatisfaction_Adminhtml_Esatisfaction_CustomquestionsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('e-Satisfaction'))->_title($this->__('Custom Questions'));
		
        $this->loadLayout();
		
        /**
         * Set active menu item
         */
		$this->_setActiveMenu('esatisfaction');

        /**
         * Add breadcrumb item
         */
        $this->_addBreadcrumb(Mage::helper('esatisfaction')->__('e-Satisfaction'), Mage::helper('esatisfaction')->__('e-Satisfaction'));
        $this->_addBreadcrumb(Mage::helper('esatisfaction')->__('Custom Questions'), Mage::helper('esatisfaction')->__('Custom Questions'));
		
        $this->renderLayout();
    }
	
	
	public function exportAction()
    {
		
		
		$site_id = Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_site_id');
		$quest_id = Mage::app()->getRequest()->getParam('quest_id');
		$stage = Mage::app()->getRequest()->getParam('stage');
		$url = 'https://www.e-satisfaction.gr/api/v2/prestashop/exported_question_section/'.$site_id.'?stage='.$stage.'&quest_id='.$quest_id;

	
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
		
        $csv_data = json_decode(utf8_encode($result), true);
		
		// Add data to $csv_data

		$fileName = "answers_for_question_".$quest_id.".csv";
	
		$var_csv = new Varien_File_Csv();

		$var_csv->saveData($fileName, $csv_data);

		$this->_prepareDownloadResponse($fileName, array('type' => 'filename', 'value' => $fileName));
		
    }
	
	
}
?>