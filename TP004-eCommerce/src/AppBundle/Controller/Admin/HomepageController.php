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

        return $this->render('admin/homepage/index.html.twig');

    }
}