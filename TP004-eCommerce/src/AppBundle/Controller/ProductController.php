<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 13/04/17
 * Time: 15:14
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package AppBundle\Controller
 */
class ProductController extends Controller{

    /**
     * @Route("/search", name="app.product.search")
     */
    public function searchAction(Request $request) {

        $locale = $request->getLocale();
        $search = $request->get('search');
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->searchProductBy($locale, $search);

        return $this->render('product/search.html.twig', [
            'products' => $products
        ]);


    }

    /**
     * @Route("/shop/{category}/{subcategory}/{product}", name="app.product.index")
     */
    public function indexAction(Request $request, $category, $subcategory, $product) {

        $locale = $request->getLocale();
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->getOneProduct($locale, $product);
        $maxAvailableOrder = $this->get('app.product.string.utils')->maxAvailableOrder($product->getStock());

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'category' => $category,
            'subcategory' => $subcategory,
            'maxAvailableOrder' => $maxAvailableOrder
        ]);
    }
}