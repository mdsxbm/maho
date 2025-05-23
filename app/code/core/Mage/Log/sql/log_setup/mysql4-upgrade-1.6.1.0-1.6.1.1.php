<?php

/**
 * Maho
 *
 * @package    Mage_Log
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Core_Model_Resource_Setup $this */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->changeColumn(
    $installer->getTable('log/visitor_info'),
    'server_addr',
    'server_addr',
    'varbinary(16)',
);

$installer->getConnection()->update(
    $installer->getTable('log/visitor_info'),
    [
        'server_addr' => new Zend_Db_Expr('UNHEX(HEX(CAST(server_addr as UNSIGNED INT)))'),
    ],
);

$installer->getConnection()->changeColumn(
    $installer->getTable('log/visitor_info'),
    'remote_addr',
    'remote_addr',
    'varbinary(16)',
);

$installer->getConnection()->update(
    $installer->getTable('log/visitor_info'),
    [
        'remote_addr' => new Zend_Db_Expr('UNHEX(HEX(CAST(remote_addr as UNSIGNED INT)))'),
    ],
);

$installer->getConnection()->changeColumn(
    $installer->getTable('log/visitor_online'),
    'remote_addr',
    'remote_addr',
    'varbinary(16)',
);

$installer->getConnection()->update(
    $installer->getTable('log/visitor_online'),
    [
        'remote_addr' => new Zend_Db_Expr('UNHEX(HEX(CAST(remote_addr as UNSIGNED INT)))'),
    ],
);

$installer->endSetup();
