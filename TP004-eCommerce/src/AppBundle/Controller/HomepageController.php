<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="app.homepage.index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('homepage/index.html.twig', [

        ]);
    }
}
