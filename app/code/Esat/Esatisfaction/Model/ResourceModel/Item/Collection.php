<?php

namespace Esat\Esatisfaction\Model\ResourceModel\Item;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'item_id';
    protected $_eventPrefix = 'esat_esatisfaction_item_collection';
    protected $_eventObject = 'item_collection';

    /**
     * Define resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Esat\Esatisfaction\Model\Item', 'Esat\Esatisfaction\Model\ResourceModel\Item');
    }
}
