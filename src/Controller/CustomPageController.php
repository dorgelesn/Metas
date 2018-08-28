<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Page;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomPageController extends AbstractController
{

    /**
    * @Route("/contribution/addpratical", name="addPratical")
    */
    public function addPratical()
    {
       
        return $this->render('pages/custom/addpratical.html.twig');
    }

    /**
     * @Route("/contribution/addgoodplan", name="addgoodplan")
     */
    public function addGoodPlan()
    {

        return $this->render('pages/custom/addgoodplan.html.twig');
    }


    /**
     * check and persit data of entity page in database
     *@param Request $request to get data from the form
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
    */
    public function add_page(Request $request)
    {

        $description = $request->get('_description');
        $idCategory = $request->get('idcategory');

        $category = $this->getDoctrine()->getRepository(Category::class)->find($idCategory);// 1 corresponds to the id of pratical page
       if(!is_null($description))
       {

          $entityManager = $this->getDoctrine()->getManager();

          $page = new Page();

          $page->setContent($description);
          $page->setCategory($category);

          $entityManager->persist($page);
          $entityManager->flush();

           $this->addFlash(
               'notice',
               'Nouvelle information pratique ajouté'
           );
           return $this->redirectToRoute('space');
       }

    }

    /**
     * This function validate the contains of a page form
     * @param Request $request to get data from the form
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function mod_page(Request $request)
    {

        $description = $request->get('_description');



        $page = $this->getDoctrine()->getRepository(Page::class)->find($request->get('id'));


        if (!$page) {
            throw $this->createNotFoundException(
                'No page found for id '.$request->get('id')
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $page->setContent($description);

        $entityManager->flush();

            $this->addFlash(
                'notice',
                'l\'unité a été modifiée!'
            );


      ;
        return $this->redirectToRoute('space');


    }

    /**
     * @Route("/page/mod", name="page_mod")
     */
    public function modify(Request $request)
    {

        $page = $this->getDoctrine()->getRepository(Page::class)->find($request->get('id'));

        if(!is_null($page))
        {

            return $this->render('pages/custom/addpratical.html.twig',array('pratical'=>$page));

        }else
            throw new \Exception('This page is not defined!');

    }

    /**
     * @Route("/pratical/search", name="pratical_search")
     */

    public function search(Request $request)
    {
/*

            $courses = $this->getDoctrine()->getRepository(Course::class)->findByTitre($request->get('keyword'));
            var_dump($courses);
            die();

        return $this->render('pages/user/userspace.html.twig',array('courses' => $courses));

*/
    }





    public function delete(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $page = $this->getDoctrine()->getRepository(Page::class)->find($request->get('id'));


        $entityManager->remove($page);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'L\'unité selectionné a été supprimé!!!'
        );
        return $this->redirectToRoute('space');
    }

}