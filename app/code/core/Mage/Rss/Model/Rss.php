<?php

/**
 * Maho
 *
 * @package    Mage_Rss
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2017-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Rss_Model_Rss
{
    protected $_feedArray = [];

    /**
     * @param array $data
     * @return $this
     */
    public function _addHeader($data = [])
    {
        $this->_feedArray = $data;
        return $this;
    }

    /**
     * @param array $entries
     * @return $this
     */
    public function _addEntries($entries)
    {
        $this->_feedArray['entries'] = $entries;
        return $this;
    }

    /**
     * @param array $entry
     * @return $this
     */
    public function _addEntry($entry)
    {
        $this->_feedArray['entries'][] = $entry;
        return $this;
    }

    /**
     * @return array
     */
    public function getFeedArray()
    {
        return $this->_feedArray;
    }

    /**
     * @return string
     */
    public function createRssXml()
    {
        try {
            $rssFeedFromArray = Zend_Feed::importArray($this->getFeedArray(), 'rss');
            return $rssFeedFromArray->saveXml();
        } catch (Exception $e) {
            return Mage::helper('rss')->__('Error in processing xml. %s', $e->getMessage());
        }
    }
}
