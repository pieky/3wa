<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 25/04/17
 * Time: 11:49
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\SubCategory;
use AppBundle\Form\SubCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SubCategoryController extends Controller {

    /**
     * @Route("/subcategory", name="app.admin.subcategory.index")
     */
    public function indexAction(Request $request) {

        $locale = $request->getLocale();
        $subcategories = $this->getDoctrine()->getRepository('AppBundle:SubCategory')->getAllSubCategoriesByLocale($locale);
        $categories = $this->getDoctrine()->getRepository('AppBundle:SubCategory')->getAllSubCategoriesCategoryByLocale($locale);

        return $this->render('admin/subcategory/index.html.twig', [
            'subcategories' => $subcategories,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/subcategory/add", name="app.admin.subcategory.add", defaults={"id"=null})
     * @Route("/subcategory/update/{id}", name="app.admin.subcategory.update")
     */
    public function formAction(Request $request, $id) {

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository('AppBundle:SubCategory');

        $translator = $this->get('translator.default');

        $entity = $id ? $rc->find($id) : new SubCategory();
        $isNew = $id ? false : true;
        $entityType = SubCategoryType::class;

        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $em->persist($data);
            $em->flush();

            $msg = $id ? $translator->trans('flashMessages.subcategory.udpate') : $translator->trans('flashMessages.subcategory.add');
            $this->addFlash('notice', $msg);
            return $this->redirectToRoute('app.admin.subcategory.index');
        }

        return $this->render('admin/subcategory/form.html.twig', [
            'form' => $form->createView(),
            'isNew' => $isNew
        ]);
    }

}