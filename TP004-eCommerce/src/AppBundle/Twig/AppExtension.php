<?php
namespace AppBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface {

    private $doctrine;
    private $twig;
    private $request;
    private $session;

    public function __construct(Registry $doctrine, \Twig_Environment $twig, RequestStack $request, Session $session){
        $this->doctrine = $doctrine;
        $this->twig = $twig;
        $this->request = $request->getMasterRequest();
        $this->session = $session;
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
            new \Twig_SimpleFilter('price_convert', [$this, 'priceConvert']),
        );
    }

    public function renderNavbar(){
        $locale = $this->request->getLocale();
        $categories = $this->doctrine->getRepository('AppBundle:Category')->getCategoriesByLocale($locale);

        return $this->twig->render('inc/navbar.html.twig', [
            'categories' => $categories
        ]);
    }

    public function priceConvert($price){

        $currency = $this->session->get('currency');
        $rate = $this->doctrine->getRepository('AppBundle:ExchangeRate')->findOneBy(['code' => $currency]);

        if($rate === null){
            return $price;
        }else{
            return $rate->getRate()*$price;
        }
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