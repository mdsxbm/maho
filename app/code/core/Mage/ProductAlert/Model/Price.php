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
 * ProductAlert for changed price model
 *
 * @package    Mage_ProductAlert
 *
 * @method Mage_ProductAlert_Model_Resource_Price _getResource()
 * @method Mage_ProductAlert_Model_Resource_Price getResource()
 * @method Mage_ProductAlert_Model_Resource_Price_Collection getCollection()
 *
 * @method int getCustomerId()
 * @method $this setCustomerId(int $value)
 * @method int getProductId()
 * @method $this setProductId(int $value)
 * @method float getPrice()
 * @method $this setPrice(float $value)
 * @method int getWebsiteId()
 * @method $this setWebsiteId(int $value)
 * @method string getAddDate()
 * @method $this setAddDate(string $value)
 * @method string getLastSendDate()
 * @method $this setLastSendDate(string $value)
 * @method int getSendCount()
 * @method $this setSendCount(int $value)
 * @method int getStatus()
 * @method $this setStatus(int $value)
 */
class Mage_ProductAlert_Model_Price extends Mage_Core_Model_Abstract
{
    #[\Override]
    protected function _construct()
    {
        $this->_init('productalert/price');
    }

    /**
     * @return Mage_ProductAlert_Model_Resource_Price_Customer_Collection
     */
    public function getCustomerCollection()
    {
        return Mage::getResourceModel('productalert/price_customer_collection');
    }

    /**
     * @return $this
     */
    public function loadByParam()
    {
        if (!is_null($this->getProductId()) && !is_null($this->getCustomerId()) && !is_null($this->getWebsiteId())) {
            $this->getResource()->loadByParam($this);
        }
        return $this;
    }

    /**
     * @param int $customerId
     * @param int $websiteId
     * @return $this
     */
    public function deleteCustomer($customerId, $websiteId = 0)
    {
        $this->getResource()->deleteCustomer($this, $customerId, $websiteId);
        return $this;
    }
}
