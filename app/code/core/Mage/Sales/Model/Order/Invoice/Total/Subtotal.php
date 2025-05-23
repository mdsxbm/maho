<?php

/**
 * Maho
 *
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2023 The OpenMage Contributors (https://openmage.org)
 * @copyright  Copyright (c) 2024 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Sales_Model_Order_Invoice_Total_Subtotal extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    /**
     * Collect invoice subtotal
     *
     * @return  Mage_Sales_Model_Order_Invoice_Total_Subtotal
     */
    #[\Override]
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $subtotal       = 0;
        $baseSubtotal   = 0;
        $subtotalInclTax = 0;
        $baseSubtotalInclTax = 0;

        $order = $invoice->getOrder();

        foreach ($invoice->getAllItems() as $item) {
            if ($item->getOrderItem()->isDummy()) {
                continue;
            }

            $item->calcRowTotal();

            $subtotal            += $item->getRowTotal();
            $baseSubtotal        += $item->getBaseRowTotal();
            $subtotalInclTax     += $item->getRowTotalInclTax() + $item->getWeeeTaxAppliedRowAmount();
            $baseSubtotalInclTax += $item->getBaseRowTotalInclTax() + $item->getBaseWeeeTaxAppliedRowAmount();
        }

        $allowedSubtotal = $order->getSubtotal() - $order->getSubtotalInvoiced();
        $baseAllowedSubtotal = $order->getBaseSubtotal() - $order->getBaseSubtotalInvoiced();

        if ($invoice->isLast()) {
            $subtotal = $allowedSubtotal;
            $baseSubtotal = $baseAllowedSubtotal;
        } else {
            $subtotal = min($allowedSubtotal, $subtotal);
            $baseSubtotal = min($baseAllowedSubtotal, $baseSubtotal);
        }

        $invoice->setSubtotal($subtotal);
        $invoice->setBaseSubtotal($baseSubtotal);
        $invoice->setSubtotalInclTax($subtotalInclTax);
        $invoice->setBaseSubtotalInclTax($baseSubtotalInclTax);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $subtotal);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseSubtotal);
        return $this;
    }
}
