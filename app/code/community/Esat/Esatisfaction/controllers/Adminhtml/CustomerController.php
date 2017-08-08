<?php 
require_once 'Mage/Adminhtml/controllers/CustomerController.php';

class Esat_Esatisfaction_Adminhtml_CustomerController extends Mage_Adminhtml_CustomerController
{
    public function esatisfactionAction() {
        $this->_initCustomer();
		
        $this->loadLayout();
        $this->renderLayout();
		
    }
}
?>