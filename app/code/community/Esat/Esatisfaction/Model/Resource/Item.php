<?php

/**
 * Class Esat_Esatisfaction_Model_Resource_Item
 */
class Esat_Esatisfaction_Model_Resource_Item extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('esatisfaction/item', 'order_item_id');
    }
}
