<?php
class Esat_Esatisfaction_Block_Adminhtml_Customquestions
    extends Mage_Core_Block_Template
{	
	public function isCustomQuestionsEnabled(){
		return Mage::getStoreConfig('esatisfaction/section_one/esatisfaction_custom_questions');
	}
}