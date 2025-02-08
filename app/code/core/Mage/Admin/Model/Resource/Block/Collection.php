<?php

/**
 * Maho
 *
 * @package    Mage_Admin
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Admin permissions block collection
 *
 * @package    Mage_Adminhtml
 */
class Mage_Admin_Model_Resource_Block_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    #[\Override]
    protected function _construct()
    {
        $this->_init('admin/block');
    }
}
