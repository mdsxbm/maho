<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml customers tagged with tag
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Tag_Customer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Mage_Adminhtml_Block_Tag_Customer constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        switch ($this->getRequest()->getParam('ret')) {
            case 'all':
                $url = $this->getUrl('*/*/');
                break;

            case 'pending':
                $url = $this->getUrl('*/*/pending');
                break;

            default:
                $url = $this->getUrl('*/*/');
        }

        $this->_block = 'tag_customer';
        $this->_controller = 'tag_customer';
        $this->_removeButton('add');
        $this->setBackUrl($url);
        $this->_addBackButton();

        $tagInfo = Mage::getModel('tag/tag')
            ->load(Mage::registry('tagId'));

        $this->_headerText = Mage::helper('tag')->__("Customers Tagged '%s'", $this->escapeHtml($tagInfo->getName()));
    }
}
