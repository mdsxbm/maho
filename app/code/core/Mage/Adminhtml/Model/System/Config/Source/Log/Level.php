<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Model_System_Config_Source_Log_Level
{
    public function toOptionArray()
    {
        $helper = Mage::helper('adminhtml');

        return [
            Zend_Log::EMERG  => $helper->__('Emergency'),
            Zend_Log::ALERT  => $helper->__('Alert'),
            Zend_Log::CRIT   => $helper->__('Critical'),
            Zend_Log::ERR    => $helper->__('Error'),
            Zend_Log::WARN   => $helper->__('Warning'),
            Zend_Log::NOTICE => $helper->__('Notice'),
            Zend_Log::INFO   => $helper->__('Informational'),
            Zend_Log::DEBUG  => $helper->__('Debug'),
        ];
    }
}
