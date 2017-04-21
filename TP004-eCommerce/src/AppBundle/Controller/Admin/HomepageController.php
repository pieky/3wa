<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class HomepageController extends Controller {

    /**
     * @Route("/", name="app.admin.homepage.index")
     */
    public function indexAction() {

        $dateNow = new \DateTime();

        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        $usersTokens = $this->getDoctrine()->getRepository('AppBundle:UserToken')->findAll();
        return $this->render('admin/homepage/index.html.twig', [
            'users' => $users,
            'usersTokens' => $usersTokens,
            'dateNow' => $dateNow
        ]);

    }
}