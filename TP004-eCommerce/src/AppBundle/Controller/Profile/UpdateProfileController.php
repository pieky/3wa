<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 21/04/17
 * Time: 16:04
 */

namespace AppBundle\Controller\Profile;

use AppBundle\Event\Account\AccountEvent;
use AppBundle\Event\Account\AccountEvents;
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
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/password", name="app.profile.update.password")
     */
    public function updatePasswordAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $entityType = UserType::class;
        $form = $this->createForm($entityType,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            /*
             * Création et lancement d'un event
             */
                //declencher l'event AccountEvent::CREATE
                $event = new AccountEvent();
                $event->setEmail($user->getEmail());
                $event->setUsername($user->getUsername());

                //service fournis par symfony. permet de déclencher l'événement
                $eventDispatcher = $this->get('event_dispatcher');

                //on emet l'événement
                $eventDispatcher->dispatch(AccountEvents::PASSWORD_CHANGE, $event);
            /*
             * Fin
             */

            $translator = $this->get('translator.default');
            $this->addFlash('notice', $translator->trans('flashMessages.user.update'));

            return $this->redirectToRoute('app.profile.homepage.index');
        }

        return $this->render('profile/update/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}