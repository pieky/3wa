<?php
/**
 * Created by PhpStorm.
 * User: zenou
 * Date: 15/04/2017
 * Time: 10:21
 */

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class UserController extends Controller {

    /**
     * @Route("/user/list", name="app.admin.user.index")
     */
    public function indexAction() {

        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }
}