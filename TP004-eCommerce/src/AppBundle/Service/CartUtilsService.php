<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/04/17
 * Time: 16:30
 */

namespace AppBundle\Service;


use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Session\Session;

class CartUtilsService {

    private $session;
    private $translator;

    public function __construct(Session $session, Translator $translator){
        $this->session = $session;
        $this->translator = $translator;
    }

    public function addToCart($id, $qte) {
        $cart = $this->session->get('cart');
        $cartQte = $this->session->get('cartQte');

        if($cart && array_key_exists($id, $cart)) {
            $qte = $cart[$id]+$qte;
            unset($cart[$id]);
        }

        $cart[$id] = $qte;
        $cartQte += $qte;

        $this->session->set('cart',$cart);
        $this->session->set('cartQte',$cartQte);
    }

    public function updateCart($id, $qte) {
        $cart = $this->session->get('cart');
        $cartQte = $this->session->get('cartQte');

        if($cart && array_key_exists($id, $cart)) {
            if($qte == 0) {
                unset($cart[$id]);
            }else {
                $cart[$id] = $qte;
            }
        }

        $this->session->set('cart',$cart);
    }

    public function deleteFromCart($id) {
        $cart = $this->session->get('cart');
        $cartQte = $this->session->get('cartQte');

        if($cart && array_key_exists($id, $cart)) {
            $cartQte -= $cart[$id];
            unset($cart[$id]);
        }

        $this->session->set('cart',$cart);
        $this->session->set('cartQte',$cartQte);
    }

}