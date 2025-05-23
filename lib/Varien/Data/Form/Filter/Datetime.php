<?php

/**
 * Maho
 *
 * @package    Varien_Data
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Varien_Data_Form_Filter_Datetime extends Varien_Data_Form_Filter_Date
{
    /**
     * Returns the result of filtering $value
     *
     * @param string|null $value
     * @return string|null
     */
    #[\Override]
    public function inputFilter($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $filterInput = new Zend_Filter_LocalizedToNormalized([
            'date_format'   => $this->_dateFormat,
            'locale'        => $this->_locale,
        ]);
        $filterInternal = new Zend_Filter_NormalizedToLocalized([
            'date_format'   => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'locale'        => $this->_locale,
        ]);

        $value = $filterInput->filter($value);
        $value = $filterInternal->filter($value);
        return $value;
    }

    /**
     * Returns the result of filtering $value
     *
     * @param string|null $value
     * @return string
     */
    #[\Override]
    public function outputFilter($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $filterInput = new Zend_Filter_LocalizedToNormalized([
            'date_format'   => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'locale'        => $this->_locale,
        ]);
        $filterInternal = new Zend_Filter_NormalizedToLocalized([
            'date_format'   => $this->_dateFormat,
            'locale'        => $this->_locale,
        ]);

        $value = $filterInput->filter($value);
        $value = $filterInternal->filter($value);
        return $value;
    }
}
