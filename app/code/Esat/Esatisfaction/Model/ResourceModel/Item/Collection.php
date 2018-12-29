<?php

namespace Esat\Esatisfaction\Model\ResourceModel\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Esat\Esatisfaction\Model\ResourceModel\Item
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'item_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'esat_esatisfaction_item_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'item_collection';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('Esat\Esatisfaction\Model\Item', 'Esat\Esatisfaction\Model\ResourceModel\Item');
    }
}
