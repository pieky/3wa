<?php

namespace AppBundle\Controller\Profile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/profile")
 * @Security("has_role('ROLE_USER')")
 */
class HomepageController extends Controller {

    /**
     * @Route("/", name="app.profile.homepage.index")
     */
    public function indexAction() {

        $user = $this->getUser();
        $translator = $this->get('translator.default');

        //if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        if($this->getUser() === $user) {
            return $this->render('profile/homepage/index.html.twig', [
                'user' => $user
            ]);
        }

        $this->addFlash('error',$translator->trans('operation.danger'));

        return $this->render('profile/homepage/index.html.twig');
    }
}