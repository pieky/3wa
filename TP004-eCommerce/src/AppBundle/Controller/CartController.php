<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/04/17
 * Time: 15:29
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CartController
 * @package AppBundle\Controller
 * @Route("/cart")
 */
class CartController extends Controller {

    /**
     * @Route("/", name="app.cart.index")
     */
    public function indexAction(Request $request){

        $session = $this->get('session');
        $cart = $session->get('cart');
        $products = null;
        $cartTotal = 0;
        if($cart){
            foreach ($cart as $id => $qte){
                $product = $this->get('doctrine')->getRepository('AppBundle:Product')->find($id);
                $product->orderQte = $qte;
                $product->orderTotal = $qte*$product->getPrice();

                $cartTotal += $product->orderTotal;
                $products[] = $product;
            }

            return $this->render('cart/index.html.twig',[
                'products' => $products,
                'cartTotal' => $cartTotal,
                'cartEmpty' => false
            ]);
        }

        return $this->render('cart/index.html.twig',[
            'cartEmpty' => true
        ]);

    }

    /**
     * @Route("/add", name="app.cart.add")
     */
    public function addAction(Request $request){

        $productQte = $request->request->get('product-qte');
        $productId = $request->request->get('product-id');
        $this->get('app.service.cart.utils')->addToCart($productId,$productQte);
        $response = $request->isXmlHttpRequest() ? new JsonResponse() : $this->redirectToRoute('app.cart.index');
        return $response;
    }

    /**
     * @Route("/update", name="app.cart.update")
     */
    public function updateAction(Request $request){

        $productQte = $request->request->get('product-qte');
        $productId = $request->request->get('product-id');
        $this->get('app.service.cart.utils')->updateCart($productId,$productQte);
        $response = $request->isXmlHttpRequest() ? new JsonResponse() : $this->redirectToRoute('app.cart.index');
        return $response;
    }

    /**
     * @Route("/delete", name="app.cart.delete")
     */
    public function deleteAction(Request $request){

        $productId = $request->request->get('product-id');
        $this->get('app.service.cart.utils')->deleteFromCart($productId);
        $response = $request->isXmlHttpRequest() ? new JsonResponse() : $this->redirectToRoute('app.cart.index');
        return $response;
    }
}