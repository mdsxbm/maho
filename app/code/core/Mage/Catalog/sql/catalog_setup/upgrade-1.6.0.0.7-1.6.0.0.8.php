<?php

/**
 * Maho
 *
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Catalog_Model_Resource_Setup $this */
$installer  = $this;
$connection = $installer->getConnection();

$connection->addIndex(
    $installer->getTable('catalog/category_product_indexer_tmp'),
    $installer->getIdxName('catalog/category_product_indexer_tmp', ['product_id', 'category_id', 'store_id']),
    ['product_id', 'category_id', 'store_id'],
);

$table = $installer->getTable('catalog/category_product_enabled_indexer_idx');
$connection->dropIndex($table, 'IDX_CATALOG_CATEGORY_PRODUCT_INDEX_ENBL_IDX_PRODUCT_ID');
$connection->addIndex(
    $table,
    $installer->getIdxName('catalog/category_product_enabled_indexer_idx', ['product_id', 'visibility']),
    ['product_id', 'visibility'],
);

$table = $installer->getTable('catalog/category_product_enabled_indexer_tmp');
$connection->dropIndex($table, 'IDX_CATALOG_CATEGORY_PRODUCT_INDEX_ENBL_TMP_PRODUCT_ID');
$connection->addIndex(
    $table,
    $installer->getIdxName('catalog/category_product_enabled_indexer_tmp', ['product_id', 'visibility']),
    ['product_id', 'visibility'],
);

$connection->addIndex(
    $installer->getTable('catalog/category_anchor_products_indexer_idx'),
    $installer->getIdxName(
        'catalog/category_anchor_products_indexer_idx',
        ['category_id', 'product_id', 'position'],
    ),
    ['category_id', 'product_id', 'position'],
);

$connection->addIndex(
    $installer->getTable('catalog/category_anchor_products_indexer_tmp'),
    $installer->getIdxName(
        'catalog/category_anchor_products_indexer_tmp',
        ['category_id', 'product_id', 'position'],
    ),
    ['category_id', 'product_id', 'position'],
);

$connection->addIndex(
    $installer->getTable('catalog/category_anchor_indexer_idx'),
    $installer->getIdxName(
        'catalog/category_anchor_indexer_idx',
        ['path', 'category_id'],
    ),
    ['path', 'category_id'],
);

$connection->addIndex(
    $installer->getTable('catalog/category_anchor_indexer_tmp'),
    $installer->getIdxName(
        'catalog/category_anchor_indexer_tmp',
        ['path', 'category_id'],
    ),
    ['path', 'category_id'],
);

$connection->addIndex(
    $installer->getTable('catalog/category'),
    $installer->getIdxName(
        'catalog/category',
        ['path', 'entity_id'],
    ),
    ['path', 'entity_id'],
);
