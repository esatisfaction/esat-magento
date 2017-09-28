<?php
class Esat_Esatisfaction_Adminhtml_Esatisfaction_SendemailsController extends Mage_Adminhtml_Controller_Action
{
	
	public function indexAction()
    {
		$template_id 	= 'esatisfaction_order_comments_answer';
		
		$email_to 		= $this->getRequest()->getPost('email_to');
		$customer_name  = $this->getRequest()->getPost('customer_name');
		$order_id   	= $this->getRequest()->getPost('order_id');
		$stage   		= $this->getRequest()->getPost('stage');
		$comment   		= $this->getRequest()->getPost('comment');
		$answer   		= $this->getRequest()->getPost('answer');
		
		$email_template  = Mage::getModel('core/email_template')->loadDefault($template_id);
		$email_template_variables = array(
			'customer_name' => $customer_name,
			'order_id' 		=> $order_id,
			'stage' 		=> $stage,
			'comment' 		=> $comment,
			'answer' 		=> $answer,
		);
		
		$sender_name = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
		$sender_email = Mage::getStoreConfig('trans_email/ident_general/email');
		$email_template->setSenderName($sender_name);
		$email_template->setSenderEmail($sender_email); 
		
		// $processedTemplate = $email_template->getProcessedTemplate($email_template_variables);
		// echo $processedTemplate;
		
		$email_template->send($email_to, $customer_name, $email_template_variables);
	
    }
	
	
}
?>