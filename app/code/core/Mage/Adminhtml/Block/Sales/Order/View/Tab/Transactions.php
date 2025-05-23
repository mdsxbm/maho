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

class Mage_Adminhtml_Block_Sales_Order_View_Tab_Transactions extends Mage_Adminhtml_Block_Sales_Transactions_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Retrieve grid url
     *
     * @return string
     */
    #[\Override]
    public function getGridUrl()
    {
        return $this->getUrl('*/sales_order/transactions', ['_current' => true]);
    }

    /**
     * Retrieve grid row url
     *
     * @return string
     */
    #[\Override]
    public function getRowUrl($item)
    {
        return $this->getUrl('*/sales_transactions/view', ['_current' => true, 'txn_id' => $item->getId()]);
    }

    /**
     * Retrieve tab label
     *
     * @return string
     */
    #[\Override]
    public function getTabLabel()
    {
        return Mage::helper('sales')->__('Transactions');
    }

    /**
     * Retrieve tab title
     *
     * @return string
     */
    #[\Override]
    public function getTabTitle()
    {
        return Mage::helper('sales')->__('Transactions');
    }

    /**
     * Check whether can show tab
     *
     * @return bool
     */
    #[\Override]
    public function canShowTab()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/transactions');
    }

    /**
     * Check whether tab is hidden
     *
     * @return bool
     */
    #[\Override]
    public function isHidden()
    {
        return !Mage::getSingleton('admin/session')->isAllowed('sales/transactions/fetch');
    }
}
