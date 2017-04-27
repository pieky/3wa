<?php

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MaintenanceCommand extends ContainerAwareCommand {

    /*
     * Configuration de la commande
     *      - setName = nom de la commande
     *      - setDescription = décrire la commande
     *      - setArgument = argument de la fonction
     *      - setOption = option de la commande précédé d'un tiret (exemple -f pour force)
     *
     */
    //*/1 * * * * php /home/wamobi4/Bureau/commerce/bin/console app:maintenance:configure true

    protected function configure(){
        $this
            ->setName('app:maintenance:switch')
            ->setDescription('Switch maintenance mode')
            ->setHelp('This command allows you to switch your website to maintenance mode')
            ->addArgument('boolean', InputArgument::REQUIRED, 'Choose TRUE/FALSE to set the maintenance mode to ON/OFF');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        /*
         * $input => permet de récupéré les argements et les options définis dans configure
         * $output => permet d'afficher une réponse dans la console
         */
        $boolean = strtolower($input->getArgument('boolean'));
        $file = __DIR__.'/../../../app/config/maintenance.yml';

        if( ($boolean != 'true' && $boolean != 'TRUE') && ($boolean != 'false' && $boolean != 'FALSE')){
            throw new InvalidArgumentException('Argument is not a boolean type');
        }

        $fileContent = file_get_contents($file);
        $newContent = preg_replace('/maintenance: (true|false)/', "maintenance: $boolean", $fileContent);

        if(file_put_contents($file, $newContent)) {
            $response = $boolean === 'true' ? 'active' : 'inactive';
            $output->writeln("Maintenance mode is <info>$response</info>");
        }
    }
}