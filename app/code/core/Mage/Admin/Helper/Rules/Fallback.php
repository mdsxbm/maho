<?php

/**
 * Maho
 *
 * @package    Mage_Admin
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Admin_Helper_Rules_Fallback extends Mage_Core_Helper_Abstract
{
    /**
     * Fallback to resource parent node
     * @param string $resourceId
     *
     * @return string
     */
    protected function _getParentResourceId($resourceId)
    {
        $resourcePathInfo = explode('/', $resourceId);
        array_pop($resourcePathInfo);
        return implode('/', $resourcePathInfo);
    }

    /**
     * Fallback resource permissions similarly to zend_acl
     * @param array $resources
     * @param string $resourceId
     * @param string $defaultValue
     *
     * @return string
     */
    public function fallbackResourcePermissions(
        &$resources,
        $resourceId,
        $defaultValue = Mage_Admin_Model_Rules::RULE_PERMISSION_DENIED,
    ) {
        if (empty($resourceId)) {
            return $defaultValue;
        }

        if (!array_key_exists($resourceId, $resources)) {
            return $this->fallbackResourcePermissions($resources, $this->_getParentResourceId($resourceId));
        }

        return $resources[$resourceId];
    }
}
