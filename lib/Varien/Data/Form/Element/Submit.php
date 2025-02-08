<?php

/**
 * Maho
 *
 * @package    Varien_Data
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Form submit element
 *
 * @package    Varien_Data
 */
class Varien_Data_Form_Element_Submit extends Varien_Data_Form_Element_Abstract
{
    /**
     * Varien_Data_Form_Element_Submit constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->setExtType('submit');
        $this->setType('submit');
    }

    /**
     * @return string
     */
    #[\Override]
    public function getHtml()
    {
        $this->addClass('submit');
        return parent::getHtml();
    }
}
