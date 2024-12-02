<?php
/**
 * Maho
 *
 * @category   Mage
 * @package    Mage_Paygate
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

// replace transaction URLs - see http://integrationwizard.x.com/sdkupdate/step3.php
foreach ([
        'pilot-payflowpro.verisign.com' => 'pilot-payflowpro.paypal.com',
        'test-payflow.verisign.com'     => 'pilot-payflowpro.paypal.com',
        'payflow.verisign.com'          => 'payflowpro.paypal.com',
         ] as $from => $to
) {
    $installer->run("
    UPDATE {$installer->getTable('core/config_data')} SET `value` = REPLACE(`value`, '{$from}', '{$to}')
    WHERE `path` = 'payment/verisign/url'
    ");
}

$installer->endSetup();
