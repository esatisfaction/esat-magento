<?php

namespace Esat\Esatisfaction\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Item
 * @package Esat\Esatisfaction\Model\ResourceModel
 */
class Item extends AbstractDb
{
    /**
     * Item constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('esatisfaction_item', 'order_item_id');
    }
}
