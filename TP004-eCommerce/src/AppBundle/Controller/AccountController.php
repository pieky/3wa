<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\PasswordResetType;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

            /*
             * TRAITEMENT DS LE LISTENER
             *
             * // encoder password
            $passwordEncoder = $this->get('security.password_encoder');
            $passwordEncrypted = $passwordEncoder->encodePassword($data, $data->getPassword());
            $data->setPassword($passwordEncrypted);

            // selection du rôle
            $role = $rcRole->findOneBy([
                'name' => 'ROLE_USER'
            ]);

            // assigner le rôle selectionné
            $id ? null : $data->addRole($role);*/

            // créer l'user
            $em->persist($data);
            $em->flush();

            // translate
            $translator = $this->get('translator.default');

            // flash msg
            $msg = $id ? $translator->trans('flashMessages.user.update') : $translator->trans('flashMessages.user.add');
            $this->addFlash('notice', $msg);

            //redirection
            return $this->redirectToRoute('app.security.login');
        }

        return $this->render('account/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("account/delete/{id}", name="app.account.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction($id){

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $entity = $doctrine->getRepository('AppBundle:User')->find($id);

        $em->remove($entity);
        $em->flush();

        $translator = $this->get('translator.default');
        $this->addFlash('notice', $translator->trans('flashMessages.user.delete'));

        return $this->redirectToRoute('app.admin.user.index');
    }

    /**
     * @Route("/account/{username}", name="app.account.index")
     */
    public function indexAction($username) {

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->loadUserByUsername($username);

        return $this->render('account/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/account/password/reset", name="app.account.passwordReset")
     */
    public function passwordResetAction(Request $request){

        $request->getSession()->remove('auth_number_failure');

        //classe du formulaire
        $formType = PasswordResetType::class;

        //formulaire
        $form = $this->createForm($formType);
        $form->handleRequest($request);

        //formulaire valide
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            exit(dump($data));
        }

        return $this->render('account/passwordReset.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
