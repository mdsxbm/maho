<?php

declare(strict_types=1);

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Block_Permissions_OrphanedResource_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('permissionsOrphanedResourceGrid');
        $this->setDefaultSort('resource_id');
        $this->setDefaultDir('asc');
    }

    #[\Override]
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('admin/rules_collection')
            ->addFieldToFilter('resource_id', ['nin' => Mage::getSingleton('admin/session')->getAcl()->getResources()])
            ->addFieldToSelect('resource_id');
        $collection->getSelect()->group('resource_id');

        /**
         * In order for mass action selection to work properly, we need to overwrite
         * the model resource $_idFieldName, from the default 'rule_id' to 'resource_id'.
         * @see Mage_Adminhtml_Block_Widget_Grid_Massaction_Abstract::getGridIdsJson()
         * @var Mage_Admin_Model_Resource_Rules $resource
         */
        $resource = $collection->getResource();
        $resource->setResourceIdAsIdFieldName();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    #[\Override]
    protected function _prepareColumns()
    {
        $this->addColumn('resource_id', [
            'header' => Mage::helper('adminhtml')->__('Orphaned Role Resource'),
            'index' => 'resource_id',
        ]);

        return parent::_prepareColumns();
    }

    #[\Override]
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('resource_id');
        $this->getMassactionBlock()->setFormFieldName('resource_id');

        $this->getMassactionBlock()->addItem('delete', [
            'label'    => Mage::helper('adminhtml')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('adminhtml')->__('Are you sure you want to do this?'),
        ]);

        return $this;
    }

    #[\Override]
    public function getRowUrl($row): string
    {
        return '';
    }
}
