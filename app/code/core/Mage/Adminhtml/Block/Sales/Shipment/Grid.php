<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024-2025 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

use Mage_Adminhtml_Block_Widget_Grid_Massaction_Abstract as MassAction;

class Mage_Adminhtml_Block_Sales_Shipment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialization
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_shipment_grid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_shipment_grid_collection';
    }

    /**
     * Prepare and set collection of grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    #[\Override]
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    #[\Override]
    protected function _prepareColumns()
    {
        $this->addColumn('increment_id', [
            'header' => Mage::helper('sales')->__('Shipment #'),
            'index' => 'increment_id',
            'filter_index' => 'main_table.increment_id',
            'type' => 'text',
        ]);

        $this->addColumn('created_at', [
            'header' => Mage::helper('sales')->__('Date Shipped'),
            'index' => 'created_at',
            'filter_index' => 'main_table.created_at',
            'type' => 'datetime',
        ]);

        $this->addColumn('order_increment_id', [
            'header' => Mage::helper('sales')->__('Order #'),
            'index' => 'order_increment_id',
            'filter_index' => 'main_table.order_increment_id',
            'type' => 'text',
            'escape' => true,
        ]);

        $this->addColumn('order_created_at', [
            'header' => Mage::helper('sales')->__('Order Date'),
            'index' => 'order_created_at',
            'filter_index' => 'main_table.order_created_at',
            'type' => 'datetime',
        ]);

        $this->addColumn('shipping_name', [
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
            'filter_index' => 'main_table.shipping_name',
        ]);

        $this->addColumn('total_qty', [
            'header' => Mage::helper('sales')->__('Total Qty'),
            'index' => 'total_qty',
            'filter_index' => 'main_table.total_qty',
            'type' => 'number',
        ]);

        $this->addColumn(
            'action',
            [
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => Mage::helper('sales')->__('View'),
                        'url' => ['base' => '*/sales_shipment/view'],
                        'field' => 'shipment_id',
                    ],
                ],
                'is_system' => true,
            ],
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * Get url for row
     *
     * @param Mage_Sales_Model_Order_Shipment $row
     * @return string|false
     */
    #[\Override]
    public function getRowUrl($row)
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/order/shipment')) {
            return false;
        }

        return $this->getUrl('*/sales_shipment/view', ['shipment_id' => $row->getId()]);
    }

    /**
     * Prepare and set options for massaction
     *
     * @return $this
     */
    #[\Override]
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('shipment_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem(MassAction::PDF_SHIPMENTS_ORDER, [
            'label' => Mage::helper('sales')->__('PDF Packingslips'),
            'url' => $this->getUrl('*/sales_shipment/pdfshipments'),
        ]);

        $this->getMassactionBlock()->addItem(MassAction::PRINT_SHIPMENT_LABEL, [
            'label' => Mage::helper('sales')->__('Print Shipping Labels'),
            'url' => $this->getUrl('*/sales_order_shipment/massPrintShippingLabel'),
        ]);

        return $this;
    }

    /**
     * Get url of grid
     *
     * @return string
     */
    #[\Override]
    public function getGridUrl()
    {
        return $this->getUrl('*/*/*', ['_current' => true]);
    }
}
