<?php

/**
 * Maho
 *
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @method Mage_Sales_Model_Order_Item getItemById(int $value)
 */
class Mage_Sales_Model_Resource_Order_Item_Collection extends Mage_Sales_Model_Resource_Order_Collection_Abstract
{
    /**
     * @var string
     */
    protected $_eventPrefix    = 'sales_order_item_collection';

    /**
     * @var string
     */
    protected $_eventObject    = 'order_item_collection';

    /**
     * Order field for setOrderFilter
     *
     * @var string
     */
    protected $_orderField     = 'order_id';

    #[\Override]
    protected function _construct()
    {
        $this->_init('sales/order_item');
    }

    /**
     * Assign parent items on after collection load
     *
     * @return $this
     */
    #[\Override]
    protected function _afterLoad()
    {
        parent::_afterLoad();
        /**
         * Assign parent items
         */
        foreach ($this as $item) {
            if ($item->getParentItemId()) {
                $item->setParentItem($this->getItemById($item->getParentItemId()));
            }
        }
        return $this;
    }

    /**
     * Set random items order
     *
     * @return $this
     */
    public function setRandomOrder()
    {
        $this->getConnection()->orderRand($this->getSelect());
        return $this;
    }

    /**
     * Set filter by item id
     *
     * @param mixed $item
     * @return $this
     */
    public function addIdFilter($item)
    {
        if (is_array($item)) {
            $this->addFieldToFilter('item_id', ['in' => $item]);
        } elseif ($item instanceof Mage_Sales_Model_Order_Item) {
            $this->addFieldToFilter('item_id', $item->getId());
        } else {
            $this->addFieldToFilter('item_id', $item);
        }
        return $this;
    }

    /**
     * Filter collection by specified product types
     *
     * @param array $typeIds
     * @return $this
     */
    public function filterByTypes($typeIds)
    {
        $this->addFieldToFilter('product_type', ['in' => $typeIds]);
        return $this;
    }

    /**
     * Filter collection by parent_item_id
     *
     * @param int $parentId
     * @return $this
     */
    public function filterByParent($parentId = null)
    {
        if (empty($parentId)) {
            $this->addFieldToFilter('parent_item_id', ['null' => true]);
        } else {
            $this->addFieldToFilter('parent_item_id', $parentId);
        }
        return $this;
    }

    /**
     * Filter only available items.
     *
     * @return $this
     */
    public function addAvailableFilter()
    {
        $fieldExpression = '(qty_shipped - qty_returned)';
        $resultCondition = $this->_getConditionSql($fieldExpression, ['gt' => 0]);
        $this->getSelect()->where($resultCondition);
        return $this;
    }

    /**
     * Filter by customerId
     *
     * @param int|array $customerId
     * @return $this
     */
    public function addFilterByCustomerId($customerId)
    {
        $this->getSelect()->joinInner(
            ['order' => $this->getTable('sales/order')],
            'main_table.order_id = order.entity_id',
            [],
        )
            ->where('order.customer_id IN(?)', $customerId);

        return $this;
    }
}
