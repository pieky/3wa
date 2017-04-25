<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 24/04/17
 * Time: 10:00
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class CategoryController extends Controller {

    /**
     * @Route("/category", name="app.admin.category.index")
     */
    public function indexAction(Request $request) {

        $locale = $request->getLocale();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->getAllCategoriesByLocale($locale);

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/add", name="app.admin.category.add", defaults={"id"=null})
     * @Route("/category/update/{id}", name="app.admin.category.update")
     */
    public function formAction(Request $request, $id) {

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository('AppBundle:Category');

        $translator = $this->get('translator.default');

        $entity = $id ? $rc->find($id) : new Category();
        $isNew = $id ? false : true;
        $entityType = CategoryType::class;

        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $em->persist($data);
            $em->flush();

            $msg = $id ? $translator->trans('flashMessages.category.udpate') : $translator->trans('flashMessages.category.add');
            $this->addFlash('notice', $msg);
            return $this->redirectToRoute('app.admin.category.index');
        }

        return $this->render('admin/category/form.html.twig', [
            'form' => $form->createView(),
            'isNew' => $isNew
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="app.admin.category.delete")
     */
    public function deleteAction($id) {

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $entity = $doctrine->getRepository('AppBundle:Category')->find($id);

        $em->remove($entity);
        $em->flush();

        $translator = $this->get('translator.default');
        $this->addFlash('notice', $translator->trans('flashMessages.category.delete'));
        return $this->redirectToRoute('app.admin.category.index');
    }
}