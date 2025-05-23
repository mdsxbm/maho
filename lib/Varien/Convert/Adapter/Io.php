<?php

/**
 * Maho
 *
 * @package    Varien_Convert
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Varien_Convert_Adapter_Io extends Varien_Convert_Adapter_Abstract
{
    #[\Override]
    public function getResource()
    {
        if (!$this->_resource) {
            $type = $this->getVar('type', 'file');
            $className = 'Varien_Io_' . ucwords($type);
            $this->_resource = new $className();
            try {
                $this->_resource->open($this->getVars());
            } catch (Exception $e) {
                $this->addException('Error occured during file opening: ' . $e->getMessage(), Varien_Convert_Exception::FATAL);
            }
        }
        return $this->_resource;
    }

    #[\Override]
    public function load()
    {
        $data = $this->getResource()->read($this->getVar('filename'));
        $filename = $this->getResource()->pwd() . '/' . $this->getVar('filename');
        if (false === $data) {
            $this->addException('Could not load file: ' . $filename, Varien_Convert_Exception::FATAL);
        } else {
            $this->addException('Loaded successfully: ' . $filename . ' [' . strlen($data) . ' byte(s)]');
        }
        $this->setData($data);
        return $this;
    }

    #[\Override]
    public function save()
    {
        $data = $this->getData();
        $filename = $this->getResource()->pwd() . '/' . $this->getVar('filename');
        $result = $this->getResource()->write($filename, $data, 0777);
        if (false === $result) {
            $this->addException('Could not save file: ' . $filename, Varien_Convert_Exception::FATAL);
        } else {
            $text = 'Saved successfully: ' . $filename . ' [' . strlen($data) . ' byte(s)]';
            if ($this->getVar('link')) {
                $text .= ' <a href="' . $this->getVar('link') . '" target="_blank">Link</a>';
            }
            $this->addException($text);
        }
        return $this;
    }
}
