<?php

/**
 * Maho
 *
 * @package    Mage_Reports
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Reports_Model_Resource_Product_Viewed_Collection extends Mage_Reports_Model_Resource_Product_Collection
{
    /**
     * List of store ids for the current collection will be filtered by
     *
     * @var array
     */
    protected $_storeIds = [];

    /**
     * Join fields
     *
     * @param string $from
     * @param string $to
     * @return $this
     */
    #[\Override]
    protected function _joinFields($from = '', $to = '')
    {
        $this->addAttributeToSelect('*')
            ->addViewsCount($from, $to);
        return $this;
    }

    /**
     * Set date range
     *
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function setDateRange($from, $to)
    {
        $this->_reset()
            ->_joinFields($from, $to);
        return $this;
    }

    /**
     * Set store ids
     *
     * @param array $storeIds
     * @return $this
     */
    public function setStoreIds($storeIds)
    {
        $storeId = array_pop($storeIds);
        $this->setStoreId($storeId);
        $this->addStoreFilter($storeId);
        $this->addStoreIds($storeId);
        return $this;
    }

    /**
     * Add store ids to filter 'report_event' data by store
     *
     * @param array|int $storeIds
     * @return $this
     */
    public function addStoreIds($storeIds)
    {
        if (is_array($storeIds)) {
            $this->_storeIds = array_merge($this->_storeIds, $storeIds);
        } else {
            $this->_storeIds[] = $storeIds;
        }
        return $this;
    }

    /**
     * Apply store filter
     *
     * @return $this
     */
    protected function _applyStoreIds()
    {
        $this->getSelect()->where('store_id IN(?)', $this->_storeIds);
        return $this;
    }

    /**
     * Apply filters
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    #[\Override]
    protected function _beforeLoad()
    {
        $this->_applyStoreIds();
        return parent::_beforeLoad();
    }
}
