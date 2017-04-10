<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * @Route("/contact/form", name="app.contact.form", defaults={"id"= null})
     * @Route("/contact/form/{id}", name="app.contact.update", requirements={"id" = "\d+"})
     */
    public function formAction(Request $request, $id){

        //BDD
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository('AppBundle:Contact');

        /*
         * 2 types de requêtes :
         *
         * $em = $doctrine->getManager();  em=entiyManager
         *  - insert
         *  - update
         *  - delete
         *
         * $rc = $doctrine->getRepository('AppBundle:Contact');
         *  - select
         *      - findAll()             => SELECT *
         *      - find(id)              => SELECT [] WHERE id = id
         *      - findOneBy(critères)   => SELECT [] limit 1
         *      - findBy(critères)      => SELECT []
         */

        //Données du formulaire
        $entity = $id ? $rc->find($id) : new Contact();
        $entityType = ContactType::class;

        //Création du formulaire
        $form = $this->createForm($entityType, $entity);

        //Recup des data de la saisie
        $form->handleRequest($request);

        //Check si formulaire est submit and valid
        if($form->isSubmitted() && $form->isValid()){

            // récupération de la saisie = création d'une instance
            $data = $form->getData();

            /*
             * insertion ds la BDD
             *
             *      persist : mise en attente de la requête; pas d'exécution
             *          > à utiliser UNIQUEMENT pour INsERT et UPDATE
             *      remove : à utiliser pur DELETE (à la place de persist)
             *      flush : exécution de la requête
             */

            $em->persist($data);
            $em->flush();

            // Lancement d'un msg flash en session
            $msg = $id ? 'Demande mise à jour' : 'Merci, message bien envoyé';
            $this->addFlash('notice', $msg);

            // redirection
            return $this->redirectToRoute('app.contact.index');
        }

        //Envoi du form au format HTML
        return $this->render('contact/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/contact", name="app.contact.index")
     */
    public function indexAction(){

        //Get doctrine
        $doctrine = $this->getDoctrine();

        // repository class : recup un select
        $rc = $doctrine->getRepository('AppBundle:Contact');

        /*
         * $rc = $doctrine->getRepository('AppBundle:Contact');
         * (méthode par defaut de la repository class)
         *  - select
         *      - findAll()                         => SELECT *
         *      - find(id)                          => SELECT [] WHERE id = id
         *      - findOneBy(array de critère)       => SELECT [] limit 1
         *      - findBy(array de critère)          => SELECT []
         */

        $results = $rc->findAll();

        return $this->render('contact/index.html.twig', [
            'contacts' => $results
        ]);
    }

    /**
     * @Route("/contact/delete/{id}", name="app.contact.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction($id){
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $entity = $doctrine->getRepository('AppBundle:Contact')->find($id);

        //SUPPRESSION DE LA BDD
        $em->remove($entity);
        $em->flush();

        // Lancement d'un msg flash en session
        $msg = 'Contact Supprimé';
        $this->addFlash('notice', $msg);

        return $this->redirectToRoute('app.contact.index');
    }
}