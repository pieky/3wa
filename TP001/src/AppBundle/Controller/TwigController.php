<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TwigController extends Controller
{
    /**
     * @Route("/twig", name="app.twig.index")
     */
    public function indexAction(){
        return $this->render('twig/index.html.twig',[
            'key' => 'value',
            'date' => new \DateTime(),
            'list' => [
                'key0' => 'value0',
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
                'key4' => 'value4'
            ]
        ]);
    }

    /**
     * @Route("/layout", name="app.twig.layout")
     */
    public function layoutAction(){
        return $this->render('twig/layout.html.twig');
    }


}