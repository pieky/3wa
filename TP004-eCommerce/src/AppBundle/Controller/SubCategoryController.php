<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SubCategoryController
 * @package AppBundle\Controller
 * @Route("/shop")
 */
class SubCategoryController extends Controller{

    /**
     * @Route("/{category}/{subcategory}", name="app.subcategory.index")
     */
    public function indexAction(Request $request, $category, $subcategory){

        $locale = $request->getLocale();
        $doctrine = $this->getDoctrine();
        $results = $doctrine->getRepository('AppBundle:SubCategory')->getOneSubCategory($locale, $subcategory);
        $products = $doctrine->getRepository('AppBundle:Product')->getProductsBySubCategory($locale, $subcategory);

        return $this->render('subcategory/index.html.twig', [
            'subcategory' => $results,
            'products' => $products
        ]);
    }
}