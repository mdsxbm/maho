<?php

/**
 * Maho
 *
 * @package    Mage_Log
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2023 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Log_Model_Adminhtml_System_Config_Source_Loglevel
{
    /**
     * Don't log anything
     */
    public const LOG_LEVEL_NONE = 0;

    /**
     * All possible logs enabled
     */
    public const LOG_LEVEL_ALL = 1;

    /**
     * Logs only visitors, needs for working compare products and customer segment's related functionality
     * (eg. shopping cart discount for segments with not logged in customers)
     */
    public const LOG_LEVEL_VISITORS = 2;

    /**
     * @var Mage_Log_Helper_Data
     */
    protected $_helper;

    /**
     * Mage_Log_Model_Adminhtml_System_Config_Source_Loglevel constructor.
     */
    public function __construct(array $data = [])
    {
        $this->_helper = !empty($data['helper']) ? $data['helper'] : Mage::helper('log');
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => $this->_helper->__('Yes'),
                'value' => self::LOG_LEVEL_ALL,
            ],
            [
                'label' => $this->_helper->__('No'),
                'value' => self::LOG_LEVEL_NONE,
            ],
            [
                'label' => $this->_helper->__('Visitors only'),
                'value' => self::LOG_LEVEL_VISITORS,
            ],
        ];
    }
}
