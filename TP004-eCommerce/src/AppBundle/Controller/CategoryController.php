<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 13/04/17
 * Time: 12:57
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller {

    /**
     * @Route("/shop/{slug}", name="app.category.index")
     */
    public function indexAction(Request $request, $slug){

        $locale = $request->getLocale();
        $doctrine = $this->getDoctrine();
        $category = $doctrine->getRepository('AppBundle:Category')->getOneCategoryByLocale($locale, $slug);

        return $this->render('category/index.html.twig', [
            'category' => $category
        ]);

    }

}