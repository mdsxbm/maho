<?php

/**
 * Maho
 *
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2023 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Catalog_Model_Api2_Product_Image_Validator_Image extends Mage_Api2_Model_Resource_Validator
{
    /**
     * Validate data. In case of validation failure return false,
     * getErrors() could be used to retrieve list of validation error messages
     *
     * @return bool
     */
    public function isValidData(array $data)
    {
        if (!isset($data['file_content']) || !isset($data['file_mime_type']) || empty($data['file_content']) ||
            empty($data['file_mime_type'])
        ) {
            $this->_addError('The image is not specified');
        }

        return !count($this->getErrors());
    }
}
