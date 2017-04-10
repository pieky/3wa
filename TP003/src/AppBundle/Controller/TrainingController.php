<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 07/04/17
 * Time: 09:16
 */

namespace AppBundle\Controller;

use AppBundle\Form\TrainingType;
use AppBundle\Entity\Training;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TrainingController extends Controller
{

    /**
     * @Route("/training", name="app.training.index")
     */
    public function indexAction(){

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository('AppBundle:Training');

        $trainings = $rc->findAll();

        return $this->render('training/index.html.twig', [
            'trainings' => $trainings
        ]);

    }

    /**
     * @Route("/training/form", name="app.training.form", defaults={"id"= null})
     * @Route("/training/form/{id}", name="app.training.edit", requirements={"id" = "\d+"})
     */
    public function formAction(Request $request, $id){

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository('AppBundle:Training');

        $entity = $id ? $rc->find($id) : new Training();
        $entityType = TrainingType::class;

        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $slugService = $this->get('app.services.slug');
            $data->setSlug(
                $slugService->slugify($data->getName())
            );

            $em->persist($data);
            $em->flush();

            $msg = $id ? 'La formation a été mise à jour' : 'Nouvelle formation bien créé';
            $this->addFlash('notice', $msg);

            return $this->redirectToRoute('app.training.index');
        }

        return $this->render('training/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/training/{slug}", name="app.training.view")
     */
    public function viewAction($slug){

        $doctrine = $this->getDoctrine();
        $entity = $doctrine->getRepository('AppBundle:Training')->findOneBy(['slug' => $slug]);

        return $this->render('training/view.html.twig', [
            'training' => $entity
        ]);
    }

    /**
     * @Route("training/delete/{id}", name="app.training.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction($id){

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $entity = $doctrine->getRepository('AppBundle:Training')->find($id);

        $em->remove($entity);
        $em->flush();

        $msg = 'Formation supprimée';
        $this->addFlash('notice', $msg);

        return $this->redirectToRoute('app.training.index');

    }
}