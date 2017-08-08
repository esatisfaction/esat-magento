<?php

class Esat_Esatisfaction_Block_Adminhtml_Customer_Edit_Tab_Esatisfaction extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_esatisfaction_grid');
        $this->setDefaultSort('created_at', 'desc');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('sales/order_grid_collection')
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect('increment_id')
            ->addFieldToSelect('customer_id')
            ->addFieldToSelect('created_at')
            ->addFieldToSelect('grand_total')
            ->addFieldToSelect('order_currency_code')
            ->addFieldToSelect('store_id')
            ->addFieldToSelect('billing_name')
            ->addFieldToSelect('shipping_name')
            ->addFieldToFilter('customer_id', Mage::registry('current_customer')->getId())
            ->setIsCustomerMode(true);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {			
        $this->addColumn('sales_order_esatisfaction_smiley', array(
            'header'    => Mage::helper('esatisfaction')->__('General'),
			'filter'   => false,
			'sortable' => false,
			'renderer' => 'esatisfaction/adminhtml_grid_renderer_sales_order_customersmiley',
            'index'     => 'sales_order_esatisfaction_customersmiley',
        ));
		
        $this->addColumn('increment_id', array(
            'header'    => Mage::helper('customer')->__('Order #'),
            'index'     => 'increment_id',
        ));	
		
        $this->addColumn('sales_order_esatisfaction_gensatisfaction', array(
            'header'    => Mage::helper('esatisfaction')->__('General Satisfaction'),
			'filter'   => false,
			'sortable' => false,
			'renderer' => 'esatisfaction/adminhtml_grid_renderer_sales_order_gensatisfaction',
            'index'     => 'sales_order_esatisfaction_gensatisfaction',
        ));
		
        $this->addColumn('sales_order_esatisfaction_nps', array(
            'header'    => Mage::helper('esatisfaction')->__('NPS'),
			'filter'   => false,
			'sortable' => false,
			'renderer' => 'esatisfaction/adminhtml_grid_renderer_sales_order_nps',
            'index'     => 'sales_order_esatisfaction_nps',
        ));
		
        $this->addColumn('sales_order_esatisfaction_comment', array(
            'header'    => Mage::helper('esatisfaction')->__('Comment'),
			'filter'   => false,
			'sortable' => false,
			'renderer' => 'esatisfaction/adminhtml_grid_renderer_sales_order_comment',
            'index'     => 'sales_order_esatisfaction_comment',
        ));
		
        $this->addColumn('sales_order_esatisfaction_schedule', array(
            'header'    => Mage::helper('esatisfaction')->__('Scheduled'),
			'filter'   => false,
			'sortable' => false,
			'renderer' => 'esatisfaction/adminhtml_grid_renderer_sales_order_schedule',
            'index'     => 'sales_order_esatisfaction_schedule',
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('customer')->__('Purchase On'),
            'index'     => 'created_at',
            'type'      => 'datetime',
        ));
       

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/esatisfaction', array('_current' => true));
    }

}
