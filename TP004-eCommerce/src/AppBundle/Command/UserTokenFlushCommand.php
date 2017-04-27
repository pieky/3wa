<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 26/04/17
 * Time: 14:51
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserTokenFlushCommand extends ContainerAwareCommand {

    protected function configure(){
        $this
            ->setName('app:user:token:flush')
            ->setDescription('Flush user\'s tokens older than 1 day')
            ->setHelp('This command will flush user\'s tokens older than a day')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $doctrine = $this->getContainer()->get('doctrine');
        $result = $doctrine->getRepository('AppBundle:UserToken')->userTokenFlushCommand();

        $output->writeln("<info>$result</info> removed from database");
    }


}