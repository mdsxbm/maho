<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Block_Sales_Order_Create_Data extends Mage_Adminhtml_Block_Sales_Order_Create_Abstract
{
    /**
     * Retrieve available currency codes
     *
     * @return array
     */
    public function getAvailableCurrencies()
    {
        $dirtyCodes = $this->getStore()->getAvailableCurrencyCodes();
        $codes = [];
        if (is_array($dirtyCodes) && count($dirtyCodes)) {
            $rates = Mage::getModel('directory/currency')->getCurrencyRates(
                Mage::app()->getStore()->getBaseCurrency(),
                $dirtyCodes,
            );
            foreach ($dirtyCodes as $code) {
                if (isset($rates[$code]) || $code == Mage::app()->getStore()->getBaseCurrencyCode()) {
                    $codes[] = $code;
                }
            }
        }
        return $codes;
    }

    /**
     * Retrieve curency name by code
     *
     * @param   string $code
     * @return  string
     */
    public function getCurrencyName($code)
    {
        return Mage::app()->getLocale()->currency($code)->getName();
    }

    /**
     * Retrieve curency name by code
     *
     * @param   string $code
     * @return  string
     */
    public function getCurrencySymbol($code)
    {
        $currency = Mage::app()->getLocale()->currency($code);
        return $currency->getSymbol() ?: $currency->getShortName();
    }

    /**
     * Retrieve current order currency code
     *
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        return $this->getStore()->getCurrentCurrencyCode();
    }
}
