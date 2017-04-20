<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
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
     * @Route("/account/edit/{id}", name="app.account.edit", requirements={"id" = "\d+"})
     */
    public function createAction(Request $request, $id) {

        $doctrine = $this->getDoctrine();
        $rcUser = $doctrine->getRepository('AppBundle:User');
        $em = $doctrine->getManager();

        $entity = $id ? $rcUser->find($id) : new User();
        $entityType = UserType::class;

        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            // créer l'user
            $em->persist($data);
            $em->flush();

            // translate
            $translator = $this->get('translator.default');

            // flash msg
            $msg = $id ? $translator->trans('flashMessages.user.update') : $translator->trans('flashMessages.user.add');
            $this->addFlash('notice', $msg);

            //redirection
            if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app.security.login');
            }
            return $this->redirectToRoute('app.admin.user.index');
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

            return $this->redirectToRoute('app.admin.user.index');
        }

        return $this->redirectToRoute('app.homepage.index');
    }

    /**
     * @Route("/account/{username}", name="app.account.index")
     */
    public function indexAction($username) {

        $translator = $this->get('translator.default');
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->loadUserByUsername($username);

        //if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        if($this->getUser() === $user) {
            return $this->render('account/index.html.twig', [
                'user' => $user
            ]);
        }

        $this->addFlash('error',$translator->trans('operation.danger'));

        return $this->render('account/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/account/password/form", name="app.account.password.form")
     */
    public function passwordFormAction(Request $request){

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
            $expirationDate = new \DateTime('tomorrow');

            $email = $data['email'];
            $user = $doctrine->getRepository('AppBundle:User')->findUserByEmail($email);
            $userToken = $doctrine->getRepository('AppBundle:UserToken')->findUserTokenByEmail($email);

            if($userToken){
                $em->remove($userToken);
                $em->flush();
            }

            if($user) {
                $userToken = new UserToken();
                $userToken->setEmail($email);
                $userToken->setToken($token);
                $userToken->setExpirationDate($expirationDate);

                $em->persist($userToken);
                $em->flush();
            }
            $this->addFlash('notice', $translator->trans('flashMessages.user.password.reset.ask'));
        }

        return $this->render('account/password.form.html.twig', [
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

        $userToken = $doctrine->getRepository('AppBundle:UserToken')->findUserTokenByEmailToken($email, $token);
        $expirationDate = $userToken['expirationDate'];

        if($userToken && $expirationDate > $dateNow){
            $user = $doctrine->getRepository('AppBundle:User')->findUserByEmail($email);

            $formType = PasswordResetType::class;
            $form = $this->createForm($formType);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();

                if($data['password'] === $data['password_confirm']){

                    $passwordEncrypted = $this->get('security.password_encoder')->encodePassword($user, $data['password']);
                    $user->setPassword($passwordEncrypted);

                    $em->persist($user);
                    $em->remove($userToken);

                    $em->flush();

                    $this->addFlash('notice', $translator->trans('flashMessages.user.password.reset.reset'));

                    return $this->redirectToRoute('app.security.login');
                }
            }

            return $this->render('account/password.reset.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $this->addFlash('error', $translator->trans('flashMessages.user.password.reset.invalid'));
        return $this->redirectToRoute('app.account.password.form');
    }
}
