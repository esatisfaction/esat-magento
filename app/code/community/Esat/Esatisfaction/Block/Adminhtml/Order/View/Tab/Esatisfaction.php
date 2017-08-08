<?php
class Esat_Esatisfaction_Block_Adminhtml_Order_View_Tab_Esatisfaction
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{    
    //change _constuct to _construct()
    public function _construct()
    {
        parent::_construct();
		$this->setTemplate('esatisfaction/sales/order/tab/esatisfaction.phtml');
    }

    public function getTabLabel() {
        return $this->__('e-satisfaction');
    }

    public function getTabTitle() {
        return $this->__('Click here to view e-satisfaction content');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getOrder(){
        return Mage::registry('current_order');
    }
} 
?>