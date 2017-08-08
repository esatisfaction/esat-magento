<?php
class Esat_Esatisfaction_Block_Adminhtml_Grid_Renderer_Sales_Order_Gensatisfaction extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('esatisfaction/grid/renderer/sales/order/gensatisfaction.phtml');
    }

    public function render(Varien_Object $row)
    {
		$this->setOrder($row);
        return $this->toHtml();
    }

    public function canRender()
    {
        return true;
    }
	
    public function setOrder($customer)
    {
        return parent::setOrder($customer);
    }

    public function getOrder()
    {
        return parent::getOrder();
    }
	
}