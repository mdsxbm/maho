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

class Mage_Tax_Model_Resource_Calculation_Rate extends Mage_Core_Model_Resource_Db_Abstract
{
    #[\Override]
    protected function _construct()
    {
        $this->_init('tax/tax_calculation_rate', 'tax_calculation_rate_id');
    }

    /**
     * Initialize unique fields
     *
     * @return $this
     */
    #[\Override]
    protected function _initUniqueFields()
    {
        $this->_uniqueFields = [[
            'field' => ['code'],
            'title' => Mage::helper('tax')->__('Code'),
        ]];
        return $this;
    }

    /**
     * Delete all rates
     *
     * @return $this
     */
    public function deleteAllRates()
    {
        $this->_getWriteAdapter()->delete($this->getMainTable());
        return $this;
    }

    /**
     * Check if this rate exists in rule
     *
     * @param  int $rateId
     * @return array
     */
    public function isInRule($rateId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('tax/tax_calculation'), ['tax_calculation_rate_id'])
            ->where('tax_calculation_rate_id = ?', $rateId);
        return $adapter->fetchCol($select);
    }
}
