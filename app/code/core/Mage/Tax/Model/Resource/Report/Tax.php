<?php

/**
 * Maho
 *
 * @package    Mage_Tax
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Tax_Model_Resource_Report_Tax extends Mage_Reports_Model_Resource_Report_Abstract
{
    #[\Override]
    protected function _construct()
    {
        $this->_init('tax/tax_order_aggregated_created', 'id');
    }

    /**
     * Aggregate Tax data
     *
     * @param mixed $from
     * @param mixed $to
     * @return $this
     */
    public function aggregate($from = null, $to = null)
    {
        Mage::getResourceModel('tax/report_tax_createdat')->aggregate($from, $to);
        Mage::getResourceModel('tax/report_tax_updatedat')->aggregate($from, $to);
        $this->_setFlagData(Mage_Reports_Model_Flag::REPORT_TAX_FLAG_CODE);

        return $this;
    }
}
