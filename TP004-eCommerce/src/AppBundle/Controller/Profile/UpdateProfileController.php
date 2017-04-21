<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 21/04/17
 * Time: 16:04
 */

namespace AppBundle\Controller\Profile;

use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/profile")
 * @Security("has_role('ROLE_USER')")
 */
class UpdateProfileController extends Controller {

    /**
     * @Route("/update", name="app.profile.update.form")
     */
    public function formAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $entityType = UserType::class;
        $form = $this->createForm($entityType,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            $translator = $this->get('translator.default');
            $this->addFlash('notice', $translator->trans('flashMessages.user.update'));

            return $this->redirectToRoute('app.profile.homepage.index');
        }

        return $this->render('profile/update/form.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}