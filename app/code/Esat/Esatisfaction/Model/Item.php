<?php

namespace Esat\Esatisfaction\Model;

class Item extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'esat_esatisfaction_item';

    protected $_cacheTag = 'esat_esatisfaction_item';

    protected $_eventPrefix = 'esat_esatisfaction_item';

    protected function _construct()
    {
        $this->_init('Esat\Esatisfaction\Model\ResourceModel\Item');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
