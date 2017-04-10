<?php

namespace AppBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface {

    // service injectés
    private $doctrine;
    private $twig;

    public function __construct(Registry $doctrine, \Twig_Environment $twig){
        $this->doctrine = $doctrine;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_menu', [$this, 'renderMenu']),
        ];
    }

    public function renderMenu(){
        $trainings = $this->doctrine->getRepository('AppBundle:Training')->findAll();
        $modules = $this->doctrine->getRepository('AppBundle:Module')->findAll();
        return $this->twig->render('inc/nav.html.twig', [
            'trainings' => $trainings,
            'modules' => $modules
        ]);
    }
}