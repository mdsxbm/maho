<?php

/**
 * Maho
 *
 * @package    Mage_ProductAlert
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @package    Mage_ProductAlert
 * @deprecated after 1.4.1.0
 * @see Mage_ProductAlert_Block_Product_View
 */
class Mage_ProductAlert_Block_Stock extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('productalert/stock.phtml');
    }

    /**
     * @return bool
     */
    public function isShow()
    {
        if (!Mage::getStoreConfig('catalog/productalert/allow_stock')) {
            return false;
        }

        if (!$product = Mage::helper('productalert')->getProduct()) {
            return false;
        }
        /** @var Mage_Catalog_Model_Product $product */

        return !$product->isSaleable();
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    #[\Override]
    public function getUrl($route = '', $params = [])
    {
        return Mage::helper('productalert')->getSaveUrl('stock');
    }
}
