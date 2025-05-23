<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Block_Sitemap extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_controller = 'sitemap';
        $this->_headerText = Mage::helper('sitemap')->__('Google Sitemap');
        $this->_addButtonLabel = Mage::helper('sitemap')->__('Add Sitemap');
        parent::__construct();
    }
}
