<?php

/**
 * Maho
 *
 * @package    Mage_CatalogIndex
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Attribute index model
 *
 * @package    Mage_CatalogIndex
 *
 * @method Mage_CatalogIndex_Model_Resource_Attribute _getResource()
 * @method Mage_CatalogIndex_Model_Resource_Attribute getResource()
 * @method $this setEntityId(int $value)
 * @method int getAttributeId()
 * @method $this setAttributeId(int $value)
 * @method int getStoreId()
 * @method $this setStoreId(int $value)
 * @method int getValue()
 * @method $this setValue(int $value)
 */
class Mage_CatalogIndex_Model_Attribute extends Mage_Core_Model_Abstract
{
    #[\Override]
    protected function _construct()
    {
        $this->_init('catalogindex/attribute');
        $this->_getResource()->setStoreId(Mage::app()->getStore()->getId());
    }

    /**
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @param string $filter
     * @param int|array $entityFilter
     * @return array
     */
    public function getFilteredEntities($attribute, $filter, $entityFilter)
    {
        return $this->_getResource()->getFilteredEntities($attribute, $filter, $entityFilter);
    }

    /**
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @param Zend_Db_Select $entityFilter
     * @return array
     */
    public function getCount($attribute, $entityFilter)
    {
        return $this->_getResource()->getCount($attribute, $entityFilter);
    }

    /**
     * @param array $optionIds
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @param mixed $entityFilter
     * @return mixed
     */
    public function checkCount($optionIds, $attribute, $entityFilter)
    {
        return $this->_getResource()->checkCount($optionIds, $attribute, $entityFilter);
    }

    /**
     * @param Mage_Eav_Model_Resource_Entity_Attribute_Collection $collection
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @param string $value
     * @return $this
     */
    public function applyFilterToCollection($collection, $attribute, $value)
    {
        $this->_getResource()->applyFilterToCollection($collection, $attribute, $value);
        return $this;
    }
}
