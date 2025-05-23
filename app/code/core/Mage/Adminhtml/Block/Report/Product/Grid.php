<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Block_Report_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productsReportGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('desc');
    }

    #[\Override]
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('reports/product_collection');
        $collection->getEntity()->setStore(0);

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    #[\Override]
    protected function _afterLoadCollection()
    {
        $totalObj = new Mage_Reports_Model_Totals();
        $this->setTotals($totalObj->countTotals($this));

        return parent::_afterLoadCollection();
    }

    #[\Override]
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', [
            'header'    => Mage::helper('reports')->__('ID'),
            'index'     => 'entity_id',
            'total'     => 'Total',
        ]);

        $this->addColumn('name', [
            'header'    => Mage::helper('reports')->__('Name'),
            'index'     => 'name',
        ]);

        $this->addColumn('viewed', [
            'header'    => Mage::helper('reports')->__('Number Viewed'),
            'width'     => '50px',
            'align'     => 'right',
            'index'     => 'viewed',
            'total'     => 'sum',
        ]);

        $this->addColumn('added', [
            'header'    => Mage::helper('reports')->__('Number Added'),
            'width'     => '50px',
            'align'     => 'right',
            'index'     => 'added',
            'total'     => 'sum',
        ]);

        $this->addColumn('purchased', [
            'header'    => Mage::helper('reports')->__('Number Purchased'),
            'width'     => '50px',
            'align'     => 'right',
            'index'     => 'purchased',
            'total'     => 'sum',
        ]);

        $this->addColumn('fulfilled', [
            'header'    => Mage::helper('reports')->__('Number Fulfilled'),
            'width'     => '50px',
            'align'     => 'right',
            'index'     => 'fulfilled',
            'total'     => 'sum',
        ]);

        $this->addColumn('revenue', [
            'header'    => Mage::helper('reports')->__('Revenue'),
            'width'     => '50px',
            'align'     => 'right',
            'index'     => 'revenue',
            'total'     => 'sum',
        ]);

        $this->setCountTotals(true);

        $this->addExportType('*/*/exportProductsCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportProductsExcel', Mage::helper('reports')->__('Excel XML'));

        return parent::_prepareColumns();
    }
}
