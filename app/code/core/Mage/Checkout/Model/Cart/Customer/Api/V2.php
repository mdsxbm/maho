<?php

/**
 * Maho
 *
 * @package    Mage_Checkout
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Checkout_Model_Cart_Customer_Api_V2 extends Mage_Checkout_Model_Cart_Customer_Api
{
    /**
     * Prepare customer entered data for implementing
     *
     * @param  object $data
     * @return array
     */
    #[\Override]
    protected function _prepareCustomerData($data)
    {
        if (($_data = get_object_vars($data)) !== null) {
            return parent::_prepareCustomerData($_data);
        }
        return [];
    }

    /**
     * Prepare customer entered data for implementing
     *
     * @param  object $data
     * @return array|null
     */
    #[\Override]
    protected function _prepareCustomerAddressData($data)
    {
        if (is_array($data)) {
            $dataAddresses = [];
            foreach ($data as $addressItem) {
                if (($_addressItem = get_object_vars($addressItem)) !== null) {
                    $dataAddresses[] = $_addressItem;
                }
            }
            return parent::_prepareCustomerAddressData($dataAddresses);
        }

        return null;
    }
}
