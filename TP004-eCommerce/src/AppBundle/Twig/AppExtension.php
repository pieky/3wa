<?php
namespace AppBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\RequestStack;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface {

    private $doctrine;
    private $twig;
    private $request;

    public function __construct(Registry $doctrine, \Twig_Environment $twig, RequestStack $request){
        $this->doctrine = $doctrine;
        $this->twig = $twig;
        $this->request = $request->getMasterRequest();
    }

    public function getGlobals(){
        return [
            'currentLocal' => $this->request->getLocale(),
            'currentRoute' => $this->request->get('_route'),
            'currentRouteParams' => $this->request->get('_route_params')
        ];
    }

    public function getFunctions(){
        return [
            new \Twig_SimpleFunction('render_navbar', [$this, 'renderNavbar']),
        ];
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('render_stock', [$this, 'renderStock'], ['is_safe' => ['html']]),
        );
    }

    public function renderNavbar(){
        $locale = $this->request->getLocale();
        $categories = $this->doctrine->getRepository('AppBundle:Category')->getCategoriesByLocale($locale);

        return $this->twig->render('inc/navbar.html.twig', [
            'categories' => $categories
        ]);
    }

    public function renderStock($stock){

        switch ($stock) {
            case 0:
                $state = 'danger';
        break;
            case $stock<10:
                $state = 'warning';
        break;
            case $stock>10:
                $state = 'success';
        break;
        }

        $stock = '<span class="label label-'.$state.'">'.$stock.'</span>';

        return $stock;
    }
}