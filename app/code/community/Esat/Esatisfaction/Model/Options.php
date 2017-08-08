<?php
class Esat_Esatisfaction_Model_Options
{
	/**
	 * Provide available options as a value/label array
	 *
	 * @return array
	*/
	public function toOptionArray()
	{
		return array(
			array('value'=>1, 'label'=>'On'),
			array('value'=>0, 'label'=>'Off'),
		);
	}
}