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

/** @var Mage_Core_Model_Resource_Setup $this */
$installer = $this;
$installer->startSetup();

$obsoleteAcl = [
    'admin/page_cache',
    'admin/system/config/moneybookers',
    'admin/system/extensions',
    'admin/system/extensions/local',
    'admin/system/extensions/custom',
    'admin/system/tools/backup',
    'admin/system/tools/backup/rollback',
    'admin/system/tools/compiler',
    'admin/xmlconnect',
    'admin/xmlconnect/mobile',
    'admin/xmlconnect/admin_connect',
    'admin/xmlconnect/queue',
    'admin/xmlconnect/history',
    'admin/xmlconnect/templates',
];

$installer->getConnection()->delete(
    $installer->getTable('admin/rule'),
    ['resource_id IN (?)' => $obsoleteAcl],
);

$installer->endSetup();
