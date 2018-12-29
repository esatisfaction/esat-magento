<?php

namespace Esat\Esatisfaction\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Item
 * @package Esat\Esatisfaction\Model
 */
class Item extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'esat_esatisfaction_item';

    /**
     * @var string
     */
    protected $_cacheTag = 'esat_esatisfaction_item';

    /**
     * @var string
     */
    protected $_eventPrefix = 'esat_esatisfaction_item';

    protected function _construct()
    {
        $this->_init('Esat\Esatisfaction\Model\ResourceModel\Item');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
