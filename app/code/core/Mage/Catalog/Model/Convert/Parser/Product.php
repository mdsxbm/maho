<?php

/**
 * Maho
 *
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2018-2023 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Catalog_Model_Convert_Parser_Product extends Mage_Eav_Model_Convert_Parser_Abstract
{
    public const MULTI_DELIMITER = ' , ';

    protected $_resource;

    /**
     * Product collections per store
     *
     * @var array
     */
    protected $_collections;

    /**
     * Product Type Instances object cache
     *
     * @var array
     */
    protected $_productTypeInstances = [];

    /**
     * Product Type cache
     *
     * @var array|null
     */
    protected $_productTypes;

    protected $_inventoryFields = [];

    protected $_imageFields = [];

    protected $_systemFields = [];
    protected $_internalFields = [];
    protected $_externalFields = [];

    protected $_inventoryItems = [];

    protected $_productModel;

    protected $_setInstances = [];

    protected $_store;
    protected $_storeId;
    protected $_attributes = [];

    public function __construct()
    {
        foreach (Mage::getConfig()->getFieldset('catalog_product_dataflow', 'admin') as $code => $node) {
            if ($node->is('inventory')) {
                $this->_inventoryFields[] = $code;
                if ($node->is('use_config')) {
                    $this->_inventoryFields[] = 'use_config_' . $code;
                }
            }
            if ($node->is('internal')) {
                $this->_internalFields[] = $code;
            }
            if ($node->is('system')) {
                $this->_systemFields[] = $code;
            }
            if ($node->is('external')) {
                $this->_externalFields[$code] = $code;
            }
            if ($node->is('img')) {
                $this->_imageFields[] = $code;
            }
        }
    }

    /**
     * @return Mage_Catalog_Model_Mysql4_Convert
     */
    public function getResource()
    {
        if (!$this->_resource) {
            $this->_resource = Mage::getResourceSingleton('catalog_entity/convert');
            #->loadStores()
            #->loadProducts()
            #->loadAttributeSets()
            #->loadAttributeOptions();
        }
        return $this->_resource;
    }

    /**
     * @param int $storeId
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getCollection($storeId)
    {
        if (!isset($this->_collections[$storeId])) {
            $this->_collections[$storeId] = Mage::getResourceModel('catalog/product_collection');
            $this->_collections[$storeId]->getEntity()->setStore($storeId);
        }
        return $this->_collections[$storeId];
    }

    /**
     * Retrieve product type options
     *
     * @return array
     */
    public function getProductTypes()
    {
        if (is_null($this->_productTypes)) {
            $this->_productTypes = Mage::getSingleton('catalog/product_type')
                ->getOptionArray();
        }
        return $this->_productTypes;
    }

    /**
     * Retrieve Product type name by code
     *
     * @param string $code
     * @return string
     */
    public function getProductTypeName($code)
    {
        $productTypes = $this->getProductTypes();
        return $productTypes[$code] ?? false;
    }

    /**
     * Retrieve product type code by name
     *
     * @param string $name
     * @return string|false
     */
    public function getProductTypeId($name)
    {
        $productTypes = $this->getProductTypes();
        if ($code = array_search($name, $productTypes)) {
            return $code;
        }
        return false;
    }

    /**
     * Retrieve product model cache
     *
     * @return Mage_Catalog_Model_Product|object
     */
    public function getProductModel()
    {
        if (is_null($this->_productModel)) {
            $productModel = Mage::getModel('catalog/product');
            $this->_productModel = Mage::objects()->save($productModel);
        }
        return Mage::objects()->load($this->_productModel);
    }

    /**
     * Retrieve current store model
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        if (is_null($this->_store)) {
            try {
                $store = Mage::app()->getStore($this->getVar('store'));
            } catch (Exception $e) {
                $this->addException(
                    Mage::helper('catalog')->__('Invalid store specified'),
                    Varien_Convert_Exception::FATAL,
                );
                throw $e;
            }
            $this->_store = $store;
        }
        return $this->_store;
    }

    /**
     * Retrieve store ID
     *
     * @return int
     */
    public function getStoreId()
    {
        if (is_null($this->_storeId)) {
            $this->_storeId = $this->getStore()->getId();
        }
        return $this->_storeId;
    }

    /**
     * ReDefine Product Type Instance to Product
     *
     * @return $this
     */
    public function setProductTypeInstance(Mage_Catalog_Model_Product $product)
    {
        $type = $product->getTypeId();
        if (!isset($this->_productTypeInstances[$type])) {
            $this->_productTypeInstances[$type] = Mage::getSingleton('catalog/product_type')
                ->factory($product, true);
        }
        $product->setTypeInstance($this->_productTypeInstances[$type], true);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttributeSetInstance()
    {
        $productType = $this->getProductModel()->getType();
        $attributeSetId = $this->getProductModel()->getAttributeSetId();

        if (!isset($this->_setInstances[$productType][$attributeSetId])) {
            $this->_setInstances[$productType][$attributeSetId] =
                Mage::getSingleton('catalog/product_type')->factory($this->getProductModel());
        }

        return $this->_setInstances[$productType][$attributeSetId];
    }

    /**
     * Retrieve eav entity attribute model
     *
     * @param string $code
     * @return Mage_Eav_Model_Entity_Attribute
     */
    public function getAttribute($code)
    {
        if (!isset($this->_attributes[$code])) {
            $this->_attributes[$code] = $this->getProductModel()->getResource()->getAttribute($code);
        }
        return $this->_attributes[$code];
    }

    /**
     * @deprecated not used anymore
     */
    #[\Override]
    public function parse()
    {
        $data            = $this->getData();
        $entityTypeId    = Mage::getSingleton('eav/config')->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getId();
        $inventoryFields = [];

        foreach ($data as $i => $row) {
            $this->setPosition('Line: ' . ($i + 1));
            try {
                // validate SKU
                if (empty($row['sku'])) {
                    $this->addException(
                        Mage::helper('catalog')->__('Missing SKU, skipping the record.'),
                        Mage_Dataflow_Model_Convert_Exception::ERROR,
                    );
                    continue;
                }
                $this->setPosition('Line: ' . ($i + 1) . ', SKU: ' . $row['sku']);

                // try to get entity_id by sku if not set
                if (empty($row['entity_id'])) {
                    $row['entity_id'] = $this->getResource()->getProductIdBySku($row['sku']);
                }

                // if attribute_set not set use default
                if (empty($row['attribute_set'])) {
                    $row['attribute_set'] = 'Default';
                }
                // get attribute_set_id, if not throw error
                $row['attribute_set_id'] = $this->getAttributeSetId($entityTypeId, $row['attribute_set']);
                if (!$row['attribute_set_id']) {
                    $this->addException(
                        Mage::helper('catalog')->__('Invalid attribute set specified, skipping the record.'),
                        Mage_Dataflow_Model_Convert_Exception::ERROR,
                    );
                    continue;
                }

                if (empty($row['type'])) {
                    $row['type'] = 'Simple';
                }
                // get product type_id, if not throw error
                $row['type_id'] = $this->getProductTypeId($row['type']);
                if (!$row['type_id']) {
                    $this->addException(
                        Mage::helper('catalog')->__('Invalid product type specified, skipping the record.'),
                        Mage_Dataflow_Model_Convert_Exception::ERROR,
                    );
                    continue;
                }

                // get store ids
                $storeIds = $this->getStoreIds($row['store'] ?? $this->getVar('store'));
                if (!$storeIds) {
                    $this->addException(
                        Mage::helper('catalog')->__('Invalid store specified, skipping the record.'),
                        Mage_Dataflow_Model_Convert_Exception::ERROR,
                    );
                    continue;
                }

                // import data
                $rowError = false;
                foreach ($storeIds as $storeId) {
                    $collection = $this->getCollection($storeId);
                    $entity = $collection->getEntity();

                    $model = Mage::getModel('catalog/product');
                    $model->setStoreId($storeId);
                    if (!empty($row['entity_id'])) {
                        $model->load($row['entity_id']);
                    }
                    foreach ($row as $field => $value) {
                        $attribute = $entity->getAttribute($field);

                        if (!$attribute) {
                            //$inventoryFields[$row['sku']][$field] = $value;

                            if (in_array($field, $this->_inventoryFields)) {
                                $inventoryFields[$row['sku']][$field] = $value;
                            }
                            continue;
                        }
                        if ($attribute->usesSource()) {
                            $source = $attribute->getSource();
                            $optionId = $this->getSourceOptionId($source, $value);
                            if (is_null($optionId)) {
                                $rowError = true;
                                $this->addException(
                                    Mage::helper('catalog')->__('Invalid attribute option specified for attribute %s (%s), skipping the record.', $field, $value),
                                    Mage_Dataflow_Model_Convert_Exception::ERROR,
                                );
                                continue;
                            }
                            $value = $optionId;
                        }
                        $model->setData($field, $value);
                    }//foreach ($row as $field=>$value)

                    if (!$rowError) {
                        $collection->addItem($model);
                    }
                    unset($model);
                } //foreach ($storeIds as $storeId)
            } catch (Exception $e) {
                if (!$e instanceof Mage_Dataflow_Model_Convert_Exception) {
                    $this->addException(
                        Mage::helper('catalog')->__('Error during retrieval of option value: %s', $e->getMessage()),
                        Mage_Dataflow_Model_Convert_Exception::FATAL,
                    );
                }
            }
        }

        // set importinted to adaptor
        if (count($inventoryFields)) {
            Mage::register('current_imported_inventory', $inventoryFields);
            //$this->setInventoryItems($inventoryFields);
        } // end setting imported to adaptor

        $this->setData($this->_collections);
        return $this;
    }

    /**
     * @param array $items
     */
    public function setInventoryItems($items)
    {
        $this->_inventoryItems = $items;
    }

    /**
     * @return array
     */
    public function getInventoryItems()
    {
        return $this->_inventoryItems;
    }

    /**
     * Unparse (prepare data) loaded products
     *
     * @return $this
     */
    #[\Override]
    public function unparse()
    {
        $entityIds = $this->getData();

        foreach ($entityIds as $i => $entityId) {
            $product = $this->getProductModel()
                ->setStoreId($this->getStoreId())
                ->load($entityId);
            $this->setProductTypeInstance($product);
            /** @var Mage_Catalog_Model_Product $product */

            $position = Mage::helper('catalog')->__('Line %d, SKU: %s', ($i + 1), $product->getSku());
            $this->setPosition($position);

            $row = [
                'store'         => $this->getStore()->getCode(),
                'websites'      => '',
                'attribute_set' => $this->getAttributeSetName(
                    $product->getEntityTypeId(),
                    $product->getAttributeSetId(),
                ),
                'type'          => $product->getTypeId(),
                'category_ids' => implode(',', $product->getCategoryIds()),
            ];

            if ($this->getStore()->getCode() == Mage_Core_Model_Store::ADMIN_CODE) {
                $websiteCodes = [];
                foreach ($product->getWebsiteIds() as $websiteId) {
                    $websiteCode = Mage::app()->getWebsite($websiteId)->getCode();
                    $websiteCodes[$websiteCode] = $websiteCode;
                }
                $row['websites'] = implode(',', $websiteCodes);
            } else {
                $row['websites'] = $this->getStore()->getWebsite()->getCode();
                if ($this->getVar('url_field')) {
                    $row['url'] = $product->getProductUrl(false);
                }
            }

            foreach ($product->getData() as $field => $value) {
                if (in_array($field, $this->_systemFields) || is_object($value)) {
                    continue;
                }

                $attribute = $this->getAttribute($field);
                if (!$attribute) {
                    continue;
                }

                if ($attribute->usesSource()) {
                    $option = $attribute->getSource()->getOptionText($value);
                    if ($value && empty($option) && $option != '0') {
                        $this->addException(
                            Mage::helper('catalog')->__('Invalid option ID specified for %s (%s), skipping the record.', $field, $value),
                            Mage_Dataflow_Model_Convert_Exception::ERROR,
                        );
                        continue;
                    }
                    if (is_array($option)) {
                        $value = implode(self::MULTI_DELIMITER, $option);
                    } else {
                        $value = $option;
                    }
                    unset($option);
                } elseif (is_array($value)) {
                    continue;
                }

                $row[$field] = $value;
            }

            if ($stockItem = $product->getStockItem()) {
                foreach ($stockItem->getData() as $field => $value) {
                    if (in_array($field, $this->_systemFields) || is_object($value)) {
                        continue;
                    }
                    $row[$field] = $value;
                }
            }

            $productMediaGallery = $product->getMediaGallery();
            $product->reset();

            $processedImageList = [];
            foreach ($this->_imageFields as $field) {
                if (isset($row[$field])) {
                    if ($row[$field] == 'no_selection') {
                        $row[$field] = null;
                    } else {
                        $processedImageList[] = $row[$field];
                    }
                }
            }
            $processedImageList = array_unique($processedImageList);

            $batchModelId = $this->getBatchModel()->getId();
            $this->getBatchExportModel()
                ->setId(null)
                ->setBatchId($batchModelId)
                ->setBatchData($row)
                ->setStatus(1)
                ->save();

            $baseRowData = [
                'store'     => $row['store'],
                'website'   => $row['websites'],
                'sku'       => $row['sku'],
            ];
            unset($row);

            foreach ($productMediaGallery['images'] as $image) {
                if (in_array($image['file'], $processedImageList)) {
                    continue;
                }

                $rowMediaGallery = [
                    '_media_image'          => $image['file'],
                    '_media_lable'          => $image['label'],
                    '_media_position'       => $image['position'],
                    '_media_is_disabled'    => $image['disabled'],
                ];
                $rowMediaGallery = array_merge($baseRowData, $rowMediaGallery);

                $this->getBatchExportModel()
                    ->setId(null)
                    ->setBatchId($batchModelId)
                    ->setBatchData($rowMediaGallery)
                    ->setStatus(1)
                    ->save();
            }
        }

        return $this;
    }

    /**
     * Retrieve accessible external product attributes
     *
     * @return array
     */
    public function getExternalAttributes()
    {
        $productAttributes  = Mage::getResourceModel('catalog/product_attribute_collection')->load();
        $attributes         = $this->_externalFields;

        foreach ($productAttributes as $attr) {
            $code = $attr->getAttributeCode();
            if (in_array($code, $this->_internalFields) || $attr->getFrontendInput() == 'hidden') {
                continue;
            }
            $attributes[$code] = $code;
        }

        foreach ($this->_inventoryFields as $field) {
            $attributes[$field] = $field;
        }

        return $attributes;
    }
}
