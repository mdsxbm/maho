<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml customers online grid renderer for customer type.
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Customer_Online_Grid_Renderer_Type extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    #[\Override]
    public function render(Varien_Object $row)
    {
        return ($row->getCustomerId() > 0) ? Mage::helper('customer')->__('Customer') : Mage::helper('customer')->__('Visitor') ;
    }
}
