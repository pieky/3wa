<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 26/04/17
 * Time: 16:27
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class MySqlDumpCommand extends ContainerAwareCommand{

    protected function configure(){
        $this
            ->setName('app:mysql:dump')
            ->setDescription('Dump MySQL database')
            ->setHelp('This command will dump your current database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $dir = 'var/dump/mysql/';
        $date = new \DateTime();
        $date = $date->format('Y-m-d-H:i:s');
        $file = '['.$date.']TP004_backup';
        $fileSql = $file.'.sql';
        $fileZip = $file.'.zip';

        $process = new Process('mysqldump -u root -ptroiswa TP004 > '.$dir.$fileSql.' && cd '.$dir.' && zip '.$fileZip.' '.$fileSql.' && rm '.$fileSql);

        try {
            $process->run();
        } catch (\Exception $e){
            throw $e;
        }

        $mailFrom = $this->getContainer()->getParameter('contact_admin');
        $message = \Swift_Message::newInstance()
            ->setFrom($mailFrom)
            //->setTo($user->getEmail())
            ->setContentType('text/html')
            ->setSubject("MySql Dump - $date")
            ->attach(\Swift_Attachment::fromPath($dir.$fileZip))
            ->setBody('Hi,<hr><strong>Dump succeed :</strong><br>'.$process->getOutput().'<hr>')
        ;

        try {
            $this->getContainer()->get('mailer')->send($message);
        } catch (\Exception $e){
            throw $e;
        }

        $output->writeln([
            '',
            '<fg=green;options=bold,underscore>Dump succeed :</>',
            ' <info>File sent</info><comment> ->'.$process->getOutput().'</comment>',
        ]);
    }
}