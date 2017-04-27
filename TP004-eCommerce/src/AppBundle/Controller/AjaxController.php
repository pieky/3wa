<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/04/17
 * Time: 10:20
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller{

    /**
     * @Route("/ajax/change-currency", name="app.ajax.change.currency")
     */
    public function changeCurrencyAction(Request $request){

        $currency = $request->request->get('currency-choice');
        $session = $request->getSession();
        $session->set('currency',$currency);

        return new JsonResponse([
            //'currency' => $session->get('currency')
        ]);
    }
}