<?php

/**
 * Maho
 *
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2019-2023 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Quote address attribute backend resource model
 *
 * @package    Mage_Sales
 */
class Mage_Sales_Model_Resource_Quote_Address_Attribute_Backend extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    /**
     * Collect totals
     *
     * @return $this
     */
    public function collectTotals(Mage_Sales_Model_Quote_Address $address)
    {
        return $this;
    }
}
