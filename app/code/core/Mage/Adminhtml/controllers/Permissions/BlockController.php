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

class Mage_Adminhtml_Permissions_BlockController extends Mage_Adminhtml_Controller_Action
{
    /**
     * ACL resource
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     */
    public const ADMIN_RESOURCE = 'system/acl/blocks';

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/acl/blocks')
            ->_addBreadcrumb($this->__('System'), $this->__('System'))
            ->_addBreadcrumb($this->__('Permissions'), $this->__('Permissions'))
            ->_addBreadcrumb($this->__('Blocks'), $this->__('Blocks'));
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_title($this->__('System'))
            ->_title($this->__('Permissions'))
            ->_title($this->__('Blocks'));

        /** @var Mage_Adminhtml_Block_Permissions_Block $block */
        $block = $this->getLayout()->createBlock('adminhtml/permissions_block');
        $this->_initAction()
            ->_addContent($block)
            ->renderLayout();
    }

    /**
     * New action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit action
     */
    public function editAction()
    {
        $this->_title($this->__('System'))
            ->_title($this->__('Permissions'))
            ->_title($this->__('Blocks'));

        $id = (int) $this->getRequest()->getParam('block_id');
        $model = Mage::getModel('admin/block');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This block no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getBlockName() : $this->__('New Block'));

        // Restore previously entered form data from session
        $data = Mage::getSingleton('adminhtml/session')->getUserData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('permissions_block', $model);

        if (isset($id)) {
            $breadcrumb = $this->__('Edit Block');
        } else {
            $breadcrumb = $this->__('New Block');
        }
        $this->_initAction()
            ->_addBreadcrumb($breadcrumb, $breadcrumb);

        $this->getLayout()->getBlock('adminhtml.permissions.block.edit')
            ->setData('action', $this->getUrl('*/permissions_block/save'));

        $this->renderLayout();
    }

    /**
     * Save action
     *
     * @return $this|void
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $id = (int) $this->getRequest()->getParam('block_id');
            $model = Mage::getModel('admin/block')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This block no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            $model->setData($data);
            if ($id) {
                $model->setId($id);
            }
            $result = $model->validate();

            if (is_array($result)) {
                Mage::getSingleton('adminhtml/session')->setUserData($data);
                foreach ($result as $message) {
                    Mage::getSingleton('adminhtml/session')->addError($message);
                }
                $this->_redirect('*/*/edit', ['block_id' => $id]);
                return $this;
            }
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The block has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', ['block_id' => $id]);
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('block_id');
        if ($id) {
            try {
                $model = Mage::getModel('admin/block');
                $model->setId($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Block has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', ['block_id' => $id]);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find a block to delete.'));
        $this->_redirect('*/*/');
    }

    /**
     * Grid action
     */
    public function blockGridAction()
    {
        $this->getResponse()
            ->setBody($this->getLayout()
                ->createBlock('adminhtml/permissions_block_grid')
                ->toHtml());
    }

    /**
     * Controller pre-dispatch method
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    #[\Override]
    public function preDispatch()
    {
        $this->_setForcedFormKeyActions('delete');
        return parent::preDispatch();
    }
}
