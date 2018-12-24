<?php

namespace Esat\Esatisfaction\Model\ResourceModel;

class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('esatisfaction_item', 'order_item_id');
    }
}
