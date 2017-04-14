<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class HomepageController extends Controller {

    /**
     * @Route("/", name="app.admin.homepage.index")
     */
    public function indexAction() {

        return $this->render('admin/homepage/index.html.twig');

    }
}