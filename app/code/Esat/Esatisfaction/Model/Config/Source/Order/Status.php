<?php

namespace Esat\Esatisfaction\Model\Config\Source\Order;

use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

/**
 * Class Status
 * @package Esat\Esatisfaction\Model\Config\Source\Order
 */
class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $statusCollection;

    /**
     * Status constructor.
     *
     * @param CollectionFactory $statusCollectionFactory
     */
    public function __construct(CollectionFactory $statusCollectionFactory)
    {
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    /**
     * @return mixed
     */
    public function toOptionArray()
    {
        $options = $this->statusCollectionFactory->create()->toOptionArray();

        return $options;
    }
}
