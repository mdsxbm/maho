<?php

/**
 * Maho
 *
 * @package    Mage_CatalogSearch
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_CatalogSearch_Block_Term extends Mage_Core_Block_Template
{
    /**
     * @var array
     */
    protected $_terms;
    protected $_minPopularity;
    protected $_maxPopularity;

    /**
     * Load terms and try to sort it by names
     *
     * @return $this
     */
    protected function _loadTerms()
    {
        if (empty($this->_terms)) {
            $this->_terms = [];
            $terms = Mage::getResourceModel('catalogsearch/query_collection')
                ->setPopularQueryFilter(Mage::app()->getStore()->getId())
                ->setPageSize(100)
                ->load()
                ->getItems();

            if (count($terms) == 0) {
                return $this;
            }

            $this->_maxPopularity = reset($terms)->getPopularity();
            $this->_minPopularity = end($terms)->getPopularity();
            $range = $this->_maxPopularity - $this->_minPopularity;
            $range = ($range == 0) ? 1 : $range;
            $temp = [];
            /** @var Mage_CatalogSearch_Model_Query $term */
            foreach ($terms as $term) {
                if (!$term->getPopularity()) {
                    continue;
                }
                $term->setRatio(($term->getPopularity() - $this->_minPopularity) / $range);
                $temp[$term->getName()] = $term;
                $termKeys[] = $term->getName();
            }

            if (isset($termKeys)) {
                natcasesort($termKeys);
                foreach ($termKeys as $termKey) {
                    $this->_terms[$termKey] = $temp[$termKey];
                }
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getTerms()
    {
        $this->_loadTerms();
        return $this->_terms;
    }

    /**
     * @param Varien_Object $obj
     * @return string
     */
    public function getSearchUrl($obj)
    {
        $url = Mage::getModel('core/url');
        /*
        * url encoding will be done in Url.php http_build_query
        * so no need to explicitly called urlencode for the text
        */
        $url->setQueryParam('q', $obj->getName());
        return $url->getUrl('catalogsearch/result');
    }

    /**
     * @return int
     */
    public function getMaxPopularity()
    {
        return $this->_maxPopularity;
    }

    /**
     * @return int
     */
    public function getMinPopularity()
    {
        return $this->_minPopularity;
    }
}
