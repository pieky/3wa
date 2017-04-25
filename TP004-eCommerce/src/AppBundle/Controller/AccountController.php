<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use AppBundle\Event\Account\AccountEvent;
use AppBundle\Event\Account\AccountEvents;
use AppBundle\Form\PasswordFormType;
use AppBundle\Form\PasswordResetType;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccountController extends Controller
{
    /**
     * @Route("/account/create", name="app.account.create", defaults={"id"= null})
     */
    public function createAction(Request $request, $id) {

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $entity = new User();
        $entityType = UserType::class;

        $form = $this->createForm($entityType, $entity);
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
                $event->setEmail($data->getEmail());
                $event->setUsername($data->getUsername());

                //service fournis par symfony. permet de déclencher l'événement
                $eventDispatcher = $this->get('event_dispatcher');

                //on emet l'événement
                $eventDispatcher->dispatch(AccountEvents::CREATE, $event);
            /*
             * Fin
             */


            $translator = $this->get('translator.default');
            $this->addFlash('notice', $translator->trans('flashMessages.user.add'));

            if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app.security.login');
            }
            return $this->redirectToRoute('app.admin.homepage.index');
        }

        return $this->render('account/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("account/delete/{id}", name="app.account.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction($id){

        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();

            $entity = $doctrine->getRepository('AppBundle:User')->find($id);

            $em->remove($entity);
            $em->flush();

            $translator = $this->get('translator.default');
            $this->addFlash('notice', $translator->trans('flashMessages.user.delete'));

            return $this->redirectToRoute('app.admin.homepage.index');
        }

        return $this->redirectToRoute('app.homepage.index');
    }

    /**
     * @Route("/account/password/ask", name="app.account.password.ask")
     */
    public function passwordAskAction(Request $request){

        $request->getSession()->remove('auth_number_failure');
        $doctrine = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator.default');

        //classe du formulaire
        $formType = PasswordFormType::class;

        //formulaire
        $form = $this->createForm($formType);
        $form->handleRequest($request);

        //formulaire valide
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $token = $this->get('app.service.string.utils')->generateToken(16);
            $dateNow = new \DateTime();
            $expirationDate = new \DateTime();
            $expirationDate->add(new \DateInterval('P0Y0DT2H0M'));

            $email = $data['email'];
            $user = $doctrine->getRepository('AppBundle:User')->findOneBy(['email' => $email]);
            $userToken = $doctrine->getRepository('AppBundle:UserToken')->findOneBy(['email' => $email]);

            if($userToken){
                if($dateNow < $userToken->getExpirationDate()){
                    $this->addFlash('warning', $translator->trans('flashMessages.user.password.reset.exist'));
                }
                else
                {
                    $em->remove($userToken);
                    $em->flush();
                    $userToken = null;
                }
            }

            if($user and $userToken === null) {
                $userToken = new UserToken();
                $userToken->setEmail($email);
                $userToken->setToken($token);
                $userToken->setExpirationDate($expirationDate);

                $em->persist($userToken);
                $em->flush();

                $this->addFlash('notice', $translator->trans('flashMessages.user.password.reset.ask'));
            }

            if(!$user) {
                $this->addFlash('notice', $translator->trans('flashMessages.user.password.reset.ask'));
            }
        }

        return $this->render('account/password.ask.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/password/reset/{email}/{token}", name="app.security.password.reset")
     */
    public function passwordResetAction(Request $request, $email, $token) {

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $translator = $this->get('translator.default');
        $dateNow = new \DateTime();

        $userToken = $doctrine->getRepository('AppBundle:UserToken')->findOneBy([
            'email' => $email,
            'token' => $token
        ]);

        if($userToken){
            $expirationDate = $userToken->getExpirationDate();
            if($expirationDate > $dateNow) {
                $user = $doctrine->getRepository('AppBundle:User')->findOneBy(['email' => $email]);

                $formType = PasswordResetType::class;
                $form = $this->createForm($formType);
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    $data = $form->getData();

                    $passwordEncrypted = $this->get('security.password_encoder')->encodePassword($user, $data['password']);
                    $user->setPassword($passwordEncrypted);

                    $em->persist($user);
                    $em->remove($userToken);

                    $em->flush();

                    $this->addFlash('notice', $translator->trans('flashMessages.user.password.reset.reset'));

                    return $this->redirectToRoute('app.security.login');
                }

                return $this->render('account/password.reset.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        $this->addFlash('error', $translator->trans('flashMessages.user.password.reset.invalid'));
        return $this->redirectToRoute('app.account.password.ask');
    }
}
