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



        return $this->render('cart/index.html.twig');
    }

    /**
     * @Route("/add", name="app.cart.add")
     */
    public function addAction(Request $request){


/*        $session = $request->getSession();
        $cart = $session->get('cart');
        $cart[] = $slug;
        $session->set('cart',$cart);*/

        return $this->render('cart/index.html.twig');
    }

    /**
     * @Route("/delete", name="app.cart.delete")
     */
    public function deleteAction(Request $request){

        return $this->render('cart/index.html.twig');
    }

    /**
     * @Route("/update", name="app.cart.update")
     */
    public function updateAction(Request $request){

        return $this->render('cart/index.html.twig');
    }
}