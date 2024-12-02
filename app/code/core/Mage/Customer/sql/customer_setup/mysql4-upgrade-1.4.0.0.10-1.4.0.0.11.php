<?php
/**
 * Maho
 *
 * @category   Mage
 * @package    Mage_Customer
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Customer_Model_Entity_Setup $installer */
$installer = $this;

$installer->getConnection()->addColumn(
    $installer->getTable('customer/eav_attribute'),
    'data_model',
    'varchar(255) default NULL'
);

$installer->updateAttribute('customer_address', 'postcode', 'data_model', 'customer/attribute_data_postcode');
