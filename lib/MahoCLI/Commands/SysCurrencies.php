<?php

namespace Maho\Commands;

use Mage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'sys:currencies',
    description: 'Get all available currencies'
)]
class SysCurrencies extends BaseMahoCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initMaho();
        
        $currencies = Mage::app()->getLocale()->getOptionCurrencies();

        $table = new Table($output);
        $table->setHeaders(['code', 'description']);
        foreach ($currencies as $currency) {
            $table->addRow([
                $currency['value'],
                $currency['label']
            ]);
        }
        $table->render();

        return Command::SUCCESS;
    }
}