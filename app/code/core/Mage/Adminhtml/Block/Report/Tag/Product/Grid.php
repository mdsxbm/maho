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

class Mage_Adminhtml_Block_Report_Tag_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('gridProducts');
    }

    #[\Override]
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('reports/tag_product_collection');

        $collection->addUniqueTagedCount()
            ->addAllTagedCount()
            ->addStatusFilter(Mage::getModel('tag/tag')->getApprovedStatus())
            ->addGroupByProduct();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    #[\Override]
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', [
            'header'    => Mage::helper('reports')->__('ID'),
            'index'     => 'entity_id',
        ]);

        $this->addColumn('name', [
            'header'    => Mage::helper('reports')->__('Product Name'),
            'index'     => 'name',
        ]);

        $this->addColumn('utaged', [
            'header'    => Mage::helper('reports')->__('Number of Unique Tags'),
            'width'     => '50px',
            'align'     => 'right',
            'index'     => 'utaged',
        ]);

        $this->addColumn('taged', [
            'header'    => Mage::helper('reports')->__('Number of Total Tags'),
            'width'     => '50px',
            'align'     => 'right',
            'index'     => 'taged',
        ]);

        $this->addColumn(
            'action',
            [
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => [
                    [
                        'caption' => Mage::helper('catalog')->__('Show Tags'),
                        'url'     => [
                            'base' => '*/*/productDetail',
                        ],
                        'field'   => 'id',
                    ],
                ],
                'is_system' => true,
                'index'     => 'stores',
            ],
        );

        $this->setFilterVisibility(false);

        $this->addExportType('*/*/exportProductCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportProductExcel', Mage::helper('reports')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    #[\Override]
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/productDetail', ['id' => $row->getId()]);
    }
}
