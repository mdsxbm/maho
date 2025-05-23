<?php

/**
 * Maho
 *
 * @package    Mage_Customer
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Customer_Block_Account_Dashboard_Hello extends Mage_Core_Block_Template
{
    /**
     * @return string
     */
    public function getCustomerName()
    {
        return Mage::getSingleton('customer/session')->getCustomer()->getName();
    }
}
