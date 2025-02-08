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
 * Adminhtml customer billing agreement tab
 *
 * @package    Mage_Sales
 */
class Mage_Sales_Block_Adminhtml_Customer_Edit_Tab_Agreement extends Mage_Sales_Block_Adminhtml_Billing_Agreement_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Columns, that should be removed from grid
     *
     * @var array
     */
    protected $_columnsToRemove = [
        'customer_email',
        'customer_firstname',
        'customer_middlename',
        'customer_lastname',
    ];

    /**
     * Disable filters and paging
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_edit_tab_agreements');
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    #[\Override]
    public function getTabLabel()
    {
        return $this->__('Billing Agreements');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    #[\Override]
    public function getTabTitle()
    {
        return $this->__('Billing Agreements');
    }

    /**
     * Can show tab in tabs
     *
     * @return bool
     */
    #[\Override]
    public function canShowTab()
    {
        $customer = Mage::registry('current_customer');
        return (bool) $customer->getId();
    }

    /**
     * Tab is hidden
     *
     * @return bool
     */
    #[\Override]
    public function isHidden()
    {
        return false;
    }

    /**
     * @return string
     */
    #[\Override]
    public function getGridUrl()
    {
        return $this->getUrl('*/sales_billing_agreement/customerGrid', ['_current' => true]);
    }

    /**
     * Defines after which tab, this tab should be rendered
     *
     * @return string
     */
    public function getAfter()
    {
        return 'orders';
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    #[\Override]
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('sales/billing_agreement_collection')
            ->addFieldToFilter('customer_id', Mage::registry('current_customer')->getId())
            ->setOrder('created_at');
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    /**
     * Remove some columns and make other not sortable
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    #[\Override]
    protected function _prepareColumns()
    {
        $result = parent::_prepareColumns();

        foreach (array_keys($this->_columns) as $key) {
            if (in_array($key, $this->_columnsToRemove)) {
                unset($this->_columns[$key]);
            }
        }
        return $result;
    }
}
