<?php

/**
 * Maho
 *
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Sales_Block_Order_Items extends Mage_Sales_Block_Items_Abstract
{
    /**
     * Retrieve current order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    public function canDisplayGiftmessage(Mage_Sales_Model_Order_Item $item): bool
    {
        if (!$this->isModuleOutputEnabled('Mage_GiftMessage')) {
            return false;
        }
        /** @var Mage_GiftMessage_Helper_Message $helper */
        $helper = $this->helper('giftmessage/message');
        return $helper->getIsMessagesAvailable($helper::TYPE_ORDER_ITEM, $item) && $item->getGiftMessageId();
    }
}
