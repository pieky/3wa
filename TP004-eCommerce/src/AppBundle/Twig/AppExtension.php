<?php
namespace AppBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface {

    private $doctrine;
    private $twig;

    public function __construct(Registry $doctrine, \Twig_Environment $twig){
        $this->doctrine = $doctrine;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_navbar', [$this, 'renderNavbar']),
        ];
    }

    public function renderNavbar(){
        $categories = $this->doctrine->getRepository('AppBundle:Category')->findAll();

        return $this->twig->render('inc/navbar.html.twig', [
            'categories' => $categories
        ]);
    }

}