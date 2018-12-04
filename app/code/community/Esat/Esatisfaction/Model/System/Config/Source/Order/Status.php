<?php 
class Esat_Esatisfaction_Model_System_Config_Source_Order_Status
{
	public function toOptionArray()
	{
		Mage::log('My log entry');
		$order_statuses = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
		
		$statuses = array();
		foreach($order_statuses as $order_status){	
			$statuses[] = array(
				'value' => $order_status['status'], 
				'label' => $order_status['label']
			);
		}

		return $statuses;
	}
}
?>