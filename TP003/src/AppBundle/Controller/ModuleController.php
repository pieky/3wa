<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 07/04/17
 * Time: 09:26
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Module;
use AppBundle\Form\ModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends Controller{

    /**
     * @Route("/module/", name="app.module.index")
     */
    public function indexAction(){

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository('AppBundle:Module');

        $modules = $rc->findAll();

        return $this->render('module/index.html.twig', [
            'modules' => $modules
        ]);
    }

    /**
     * @Route("/module/form", name="app.module.form", defaults={"id"= null})
     * @Route("/module/form/{id}", name="app.module.edit", requirements={"id" = "\d+"})
     */
    public function formAction(Request $request, $id){
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository('AppBundle:Module');

        $entity = $id ? $rc->find($id) : new Module();
        $entityType = ModuleType::class;

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

            $msg = $id ? 'Le module a été mise à jour' : 'Nouveau module bien créé';
            $this->addFlash('notice', $msg);

            return $this->redirectToRoute('app.module.index');
        }

        return $this->render('module/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/module/{slug}", name="app.module.view")
     */
    public function viewAction($slug){

        $doctrine = $this->getDoctrine();
        $entity = $doctrine->getRepository('AppBundle:Module')->findOneBy(['slug' => $slug]);

        return $this->render('module/view.html.twig', [
            'module' => $entity
        ]);
    }



    /**
     * @Route("module/delete/{id}", name="app.module.delete", requirements={"id" = "\d+"})
     */
    public function deleteAction($id){

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $entity = $doctrine->getRepository('AppBundle:Module')->find($id);

        $em->remove($entity);
        $em->flush();

        $msg = 'Module supprimé';
        $this->addFlash('notice', $msg);

        return $this->redirectToRoute('app.module.index');

    }
}