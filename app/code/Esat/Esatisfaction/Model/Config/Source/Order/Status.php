<?php

namespace Esat\Esatisfaction\Model\Config\Source\Order;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    protected $statusCollection;

    public function __construct(\Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory)
    {
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = $this->statusCollectionFactory->create()->toOptionArray();

        return $options;
    }
}
