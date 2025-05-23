<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024-2025 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Block_Sales_Order_Status_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_status_grid');
        //$this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
        $this->setDefaultSort('state');
        $this->setDefaultDir('DESC');
    }

    #[\Override]
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('sales/order_status_collection');
        $collection->joinStates();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    #[\Override]
    protected function _prepareColumns()
    {
        $this->addColumn('label', [
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'label',
            'frame_callback' => [$this, 'decorateLabel'],
        ]);

        $this->addColumn('status', [
            'header' => Mage::helper('sales')->__('Status Code'),
            'type'  => 'text',
            'index' => 'status',
            'filter_index' => 'main_table.status',
            'width'     => '200px',
        ]);

        $this->addColumn('is_default', [
            'header'    => Mage::helper('sales')->__('Default Status'),
            'index'     => 'is_default',
            'width'     => '100px',
            'type'      => 'options',
            'options'   => [0 => $this->__('No'), 1 => $this->__('Yes')],
            'sortable'  => false,
        ]);

        $this->addColumn('state', [
            'header' => Mage::helper('sales')->__('State Code [State Title]'),
            'type'  => 'text',
            'index' => 'state',
            'width'     => '250px',
            'frame_callback' => [$this, 'decorateState'],
        ]);

        $this->addColumn('unassign', [
            'header'    => Mage::helper('sales')->__('Action'),
            'index'     => 'unassign',
            'width'     => '100px',
            'type'      => 'text',
            'frame_callback' => [$this, 'decorateAction'],
            'sortable'  => false,
            'filter'    => false,
        ]);

        return parent::_prepareColumns();
    }

    public function decorateLabel($value, $row, $column, $isExport): string
    {
        return "<span class='order-status-color-marker' style='background:{$row['color']}'></span> {$value}";
    }

    /**
     * Decorate status column values
     *
     * @return string
     */
    public function decorateState($value, $row, $column, $isExport)
    {
        if ($value) {
            $cell = $value . ' [' . Mage::getSingleton('sales/order_config')->getStateLabel($value) . ']';
        } else {
            $cell = $value;
        }
        return $cell;
    }

    public function decorateAction($value, $row, $column, $isExport)
    {
        $cell = '';
        $state = $row->getState();
        if (!empty($state)) {
            $url = $this->getUrl(
                '*/*/unassign',
                ['status' => $row->getStatus(), 'state' => $row->getState()],
            );
            $label = Mage::helper('sales')->__('Unassign');
            $cell = '<a href="' . $url . '">' . $label . '</a>';
        }
        return $cell;
    }

    /**
     * No pegination for this grid
     */
    #[\Override]
    protected function _preparePage()
    {
        return $this;
    }

    #[\Override]
    public function getRowUrl($row)
    {
        return $this->getUrl('*/sales_order_status/edit', ['status' => $row->getStatus()]);
    }
}
