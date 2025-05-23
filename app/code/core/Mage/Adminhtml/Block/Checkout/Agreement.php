<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Block_Checkout_Agreement extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller      = 'checkout_agreement';
        $this->_headerText      = Mage::helper('checkout')->__('Manage Terms and Conditions');
        $this->_addButtonLabel  = Mage::helper('checkout')->__('Add New Condition');
        parent::__construct();
    }
}
