<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2023 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Date format string
     */
    protected static $_format = null;

    /**
     * Retrieve datetime format
     *
     * @return string
     */
    protected function _getFormat()
    {
        $format = $this->getColumn()->getFormat();
        if (!$format) {
            if (is_null(self::$_format)) {
                try {
                    self::$_format = Mage::app()->getLocale()->getDateTimeFormat(
                        Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM,
                    );
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
            $format = self::$_format;
        }
        return $format;
    }

    /**
     * Renders grid column
     *
     * @return  string
     */
    #[\Override]
    public function render(Varien_Object $row)
    {
        if ($data = $this->_getValue($row)) {
            $format = $this->_getFormat();
            $useTimezone = $this->getColumn()->getUseTimezone() ?? true;
            $locale = $this->getColumn()->getLocale() ?? null;
            try {
                $data = Mage::app()->getLocale()
                    ->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT, $locale, $useTimezone)
                    ->toString($format);
            } catch (Exception $e) {
                $data = Mage::app()->getLocale()
                    ->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
            }
            return $data;
        }
        return $this->getColumn()->getDefault();
    }
}
