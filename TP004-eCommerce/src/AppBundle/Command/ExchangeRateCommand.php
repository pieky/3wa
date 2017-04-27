<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/04/17
 * Time: 12:30
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExchangeRateCommand extends ContainerAwareCommand{

    protected function configure(){
        $this
            ->setName('app:exchange:rate:update')
            ->setDescription('Update exchange rate from Fixer.io API')
            ->setHelp('This command will update exchange rate data from Fixer.io API')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $data = json_decode(file_get_contents('http://api.fixer.io/latest'));

        $currency = $this->getContainer()->getParameter('currency');
        $currencies = $this->getContainer()->getParameter('currencies');
        $search = array_search($currency, $currencies);
        array_splice($currencies, $search, 1);

        foreach ($currencies as $key => $currency) {
            $currenciesRate[$currency] = $data->rates->$currency;
        }

        $doctrine = $this->getContainer()->get('doctrine');
        $result = $doctrine->getRepository('AppBundle:ExchangeRate')->updateExchangeRateCommand($currenciesRate);

        $output->writeln([
            '',
            'Exchange rates <info>updated</info> :',
            '<comment>'.$result.'</comment>',
        ]);
    }


}