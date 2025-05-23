<?php

/**
 * Maho
 *
 * @package    Mage_Wishlist
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Wishlist_Block_Share_Email_Items extends Mage_Wishlist_Block_Abstract
{
    /**
     * Initialize template
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('wishlist/email/items.phtml');
    }

    /**
     * Retrieve Product View URL
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $additional
     * @return string
     */
    #[\Override]
    public function getProductUrl($product, $additional = [])
    {
        $additional['_store_to_url'] = true;
        return parent::getProductUrl($product, $additional);
    }

    /**
     * Retrieve URL for add product to shopping cart
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $additional
     * @return string
     */
    #[\Override]
    public function getAddToCartUrl($product, $additional = [])
    {
        return $this->getAddToCartUrlCustom($product, $additional);
    }

    /**
     * Check whether wishlist item has description
     *
     * @param Mage_Wishlist_Model_Item $item
     * @return bool
     */
    #[\Override]
    public function hasDescription($item)
    {
        $hasDescription = parent::hasDescription($item);
        if ($hasDescription) {
            return ($item->getDescription() !== Mage::helper('wishlist')->defaultCommentString());
        }
        return $hasDescription;
    }

    /**
     * Retrieve URL for add product to shopping cart with or without Form Key
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $additional
     * @param bool $addFormKey
     * @return string
     */
    #[\Override]
    public function getAddToCartUrlCustom($product, $additional = [], $addFormKey = true)
    {
        $additional['nocookie'] = 1;
        $additional['_store_to_url'] = true;
        return parent::getAddToCartUrlCustom($product, $additional, $addFormKey);
    }
}
