<?php

/**
 * Maho
 *
 * @package    Mage_Core
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2020-2025 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

abstract class Mage_Core_Model_File_Storage_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * Store media base directory path
     *
     * @var string
     */
    protected $_mediaBaseDirectory = null;

    /**
     * Retrieve media base directory path
     *
     * @return string
     */
    public function getMediaBaseDirectory()
    {
        if ($this->_mediaBaseDirectory === null) {
            /** @var Mage_Core_Helper_File_Storage_Database $helper */
            $helper = Mage::helper('core/file_storage_database');
            $this->_mediaBaseDirectory = $helper->getMediaBaseDir();
        }

        return $this->_mediaBaseDirectory;
    }

    /**
     * Collect file info
     *
     * Return array(
     *  filename    => string
     *  content     => string|bool
     *  update_time => string
     *  directory   => string
     * )
     *
     * @param  string $path
     * @return array
     */
    public function collectFileInfo($path)
    {
        $path = ltrim($path, '\\/');
        $fullPath = $this->getMediaBaseDirectory() . DS . $path;
        $io = new Varien_Io_File();
        if (!file_exists($fullPath) || !is_file($fullPath)) {
            Mage::throwException(Mage::helper('core')->__('File %s does not exist', $io->getFilteredPath($fullPath)));
        }
        if (!is_readable($fullPath)) {
            Mage::throwException(Mage::helper('core')->__('File %s is not readable', $io->getFilteredPath($fullPath)));
        }

        $path = str_replace(['/', '\\'], '/', $path);
        $directory = dirname($path);
        if ($directory == '.') {
            $directory = null;
        }

        return [
            'filename'      => basename($path),
            'content'       => @file_get_contents($fullPath),
            'update_time'   => Mage::getSingleton('core/date')->date(),
            'directory'     => $directory,
        ];
    }
}
