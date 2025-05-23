<?php

/**
 * Maho
 *
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2021-2023 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Adminhtml_Model_System_Config_Backend_Image extends Mage_Adminhtml_Model_System_Config_Backend_File
{
    /**
     * Getter for allowed extensions of uploaded files
     * @return array
     */
    #[\Override]
    protected function _getAllowedExtensions()
    {
        return Varien_Io_File::ALLOWED_IMAGES_EXTENSIONS;
    }

    /**
     * Overwritten parent method for adding validators
     */
    #[\Override]
    protected function addValidators(Mage_Core_Model_File_Uploader $uploader)
    {
        parent::addValidators($uploader);
        $validator = Mage::getModel('core/file_validator_image');
        $validator->setAllowedImageTypes($this->_getAllowedExtensions());
        $uploader->addValidateCallback(Mage_Core_Model_File_Validator_Image::NAME, $validator, 'validate');
    }
}
