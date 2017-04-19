<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 18/04/17
 * Time: 09:19
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="app.security.login")
     */
    public function loginAction(Request $request){
        if($request->getSession()->get('auth_number_failure') > 2) {
            $request->getSession()->remove('_security.last_error');
            return $this->redirectToRoute('app.account.passwordReset');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app.security.logout")
     */
    public function logoutAction(Request $request){

    }
}