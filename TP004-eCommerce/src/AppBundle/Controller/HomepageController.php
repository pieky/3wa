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
        $locale = $request->getLocale();
        $doctrine = $this->getDoctrine();
        $categories = $doctrine->getRepository('AppBundle:Category')->getCategoriesByLocale($locale);
        $randomProducts = $doctrine->getRepository('AppBundle:Product')->getRandomProducts($locale, 3);

        return $this->render('homepage/index.html.twig', [
            'categories' => $categories,
            'randomProducts' => $randomProducts
        ]);
    }

    /**
     * @Route("/updateroute", name="app.homepage.updateroute")
     */
    public function updaterouteAction(Request $request){

    }
}
