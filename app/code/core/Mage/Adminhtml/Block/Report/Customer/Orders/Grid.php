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

class Mage_Adminhtml_Block_Report_Customer_Orders_Grid extends Mage_Adminhtml_Block_Report_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('gridOrdersCustomer');
    }

    /**
     * @return $this
     */
    #[\Override]
    protected function _prepareCollection()
    {
        parent::_prepareCollection();
        $this->getCollection()->initReport('reports/customer_orders_collection');
        return $this;
    }

    #[\Override]
    protected function _prepareColumns()
    {
        $this->addColumn('name', [
            'header'    => $this->__('Customer Name'),
            'sortable'  => false,
            'index'     => 'name',
        ]);

        $this->addColumn('orders_count', [
            'header'    => $this->__('Number of Orders'),
            'width'     => '100px',
            'sortable'  => false,
            'index'     => 'orders_count',
            'total'     => 'sum',
            'type'      => 'number',
        ]);

        $baseCurrencyCode = $this->getCurrentCurrencyCode();

        $this->addColumn('orders_avg_amount', [
            'header'    => $this->__('Average Order Amount'),
            'width'     => '200px',
            'sortable'  => false,
            'type'      => 'currency',
            'currency_code'  => $baseCurrencyCode,
            'index'     => 'orders_avg_amount',
            'total'     => 'orders_sum_amount/orders_count',
            'renderer'  => 'adminhtml/report_grid_column_renderer_currency',
        ]);

        $this->addColumn('orders_sum_amount', [
            'header'    => $this->__('Total Order Amount'),
            'width'     => '200px',
            'sortable'  => false,
            'type'      => 'currency',
            'currency_code'  => $baseCurrencyCode,
            'index'     => 'orders_sum_amount',
            'total'     => 'sum',
            'renderer'  => 'adminhtml/report_grid_column_renderer_currency',
        ]);

        $this->addExportType('*/*/exportOrdersCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportOrdersExcel', Mage::helper('reports')->__('Excel XML'));

        return parent::_prepareColumns();
    }
}
