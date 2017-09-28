<?php
class Esat_Esatisfaction_Adminhtml_Esatisfaction_SaveiconController extends Mage_Adminhtml_Controller_Action
{
	
	public function indexAction()
    {		
		$order_id 		= $this->getRequest()->getPost('order_id');
		$icon_url  		= $this->getRequest()->getPost('icon_url');
		
		$orderModel = Mage::getModel('sales/order')->loadByIncrementId($order_id)->setEsatIcon($icon_url);
		
		$orderModel->setIncrementId($order_id)->save();
    }
	
	
}
?>