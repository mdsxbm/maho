<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Shipping carrier table rate grid block
 * WARNING: This grid used for export table rates
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Shipping_Carrier_Tablerate_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Website filter
     *
     * @var int|null
     */
    protected $_websiteId;

    /**
     * Condition filter
     *
     * @var string
     */
    protected $_conditionName;

    /**
     * Define grid properties
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('shippingTablerateGrid');
        $this->_exportPageSize = 10000;
    }

    /**
     * Set current website
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId)
    {
        $this->_websiteId = Mage::app()->getWebsite($websiteId)->getId();
        return $this;
    }

    /**
     * Retrieve current website id
     *
     * @return int
     */
    public function getWebsiteId()
    {
        if (is_null($this->_websiteId)) {
            $this->_websiteId = Mage::app()->getWebsite()->getId();
        }
        return $this->_websiteId;
    }

    /**
     * Set current website
     *
     * @param string $name
     * @return $this
     */
    public function setConditionName($name)
    {
        $this->_conditionName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getConditionName()
    {
        return $this->_conditionName;
    }

    /**
     * Prepare shipping table rate collection
     *
     * @return $this
     */
    #[\Override]
    protected function _prepareCollection()
    {
        /** @var Mage_Shipping_Model_Resource_Carrier_Tablerate_Collection $collection */
        $collection = Mage::getResourceModel('shipping/carrier_tablerate_collection');
        $collection->setConditionFilter($this->getConditionName())
            ->setWebsiteFilter($this->getWebsiteId());

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare table columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    #[\Override]
    protected function _prepareColumns()
    {
        $this->addColumn('dest_country', [
            'header'    => Mage::helper('adminhtml')->__('Country'),
            'index'     => 'dest_country',
            'default'   => '*',
        ]);

        $this->addColumn('dest_region', [
            'header'    => Mage::helper('adminhtml')->__('Region/State'),
            'index'     => 'dest_region',
            'default'   => '*',
        ]);

        $this->addColumn('dest_zip', [
            'header'    => Mage::helper('adminhtml')->__('Zip/Postal Code'),
            'index'     => 'dest_zip',
            'default'   => '*',
        ]);

        $label = Mage::getSingleton('shipping/carrier_tablerate')
            ->getCode('condition_name_short', $this->getConditionName());
        $this->addColumn('condition_value', [
            'header'    => $label,
            'index'     => 'condition_value',
        ]);

        $this->addColumn('price', [
            'header'    => Mage::helper('adminhtml')->__('Shipping Price'),
            'index'     => 'price',
        ]);

        return parent::_prepareColumns();
    }
}
