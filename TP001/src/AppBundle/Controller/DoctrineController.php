<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DoctrineController extends Controller {

    /**
     * @Route("/doctrine", name="app.doctrine.index")
     */
    public function indexAction(){

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository('AppBundle:Contact');

        $results = $rc->getResult();

        return $this->render('doctrine/index.html.twig',[
            'results' => $results
        ]);
    }

    /**
     * @Route("/doctrine/tpsql", name="app.doctrine.tpsql")
     */
    public function tpsqlAction(){

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository('AppBundle:Film');

        $sql1 = $rc->sql1();
        $sql2 = $rc->sql2();
        $sql3 = $rc->sql3();
        $sql4 = $rc->sql4();
        $sql5 = $rc->sql5();
        $sql6 = $rc->sql6();
        $sql7 = $rc->sql7();
        $sql8 = $rc->sql8();

        $sql10 = $rc->sql10();
        $sql11 = $rc->sql11();
        $sql12 = $rc->sql12();
        $sql13 = $rc->sql13();

        $rc = $doctrine->getRepository('AppBundle:Acteur');
        $sql9 = $rc->sql9();

        return $this->render('doctrine/tpsql.html.twig',[
            'sql1' => $sql1,
            'sql2' => $sql2,
            'sql3' => $sql3,
            'sql4' => $sql4,
            'sql5' => $sql5,
            'sql6' => $sql6,
            'sql7' => $sql7,
            'sql8' => $sql8,
            'sql9' => $sql9,
            'sql10' => $sql10,
            'sql11' => $sql11,
            'sql12' => $sql12,
            'sql13' => $sql13
        ]);
    }

}