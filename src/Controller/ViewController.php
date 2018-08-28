<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Page;
use App\Entity\View;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends AbstractController
{




    /**
     * check and persit data of entity view in database
     *@param Request $request to get data from the form
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
    */
    public function addview(Request $request)
    {

        $content = $request->get('_viewcontent');

        $course = $this->getDoctrine()->getRepository(Course::class)->find($request->get('id'));// 1 corresponds to the id of pratical page
       if(!is_null($content))
       {

          $entityManager = $this->getDoctrine()->getManager();

          $view = new View();
          $view->setContent($content);

          $course->addView($view);

          $entityManager->flush();

           $this->addFlash(
               'notice',
               'Nouvelle avis ajouté!!'
           );
           return $this->redirectToRoute('uv_details',['id' => $course->getId()]);
       }

    }

    /**
     * This function validate the contains of a page form
     * @param Request $request to get data from the form
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function modview(Request $request)
    {

        $content = $request->get('_viewcontent');
        $id = $request->get('idviewset');
        $ids = $request->get('idcourse');

        var_dump($ids);
        $view = $this->getDoctrine()->getRepository(View::class)->find( $id);
        $course = $this->getDoctrine()->getRepository(Course::class)->find( $ids);

        if (!$view) {
            throw $this->createNotFoundException(
                'No comment found for id '.$request->get('id')
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $view->setContent($content);
        $entityManager->flush();

            $this->addFlash(
                'notice',
                'le commentaire a été modifiée!'
            );
        return $this->redirectToRoute('uv_details',['id' => $course->getId()]);


    }

    /**
     * @Route("/view/mod", name="page_mod")
     */
    public function modify(Request $request)
    {

        $view = $this->getDoctrine()->getRepository(View::class)->find($request->get('id'));

        if(!is_null($view))
        {

            return $this->render('pages/view/modview.html.twig',array('pratical'=>$view));

        }else
            throw new \Exception('This page is not defined!');

    }




    /**
     * @Route("/view/delete", name="page_delete")
     */

    public function delete(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $page = $this->getDoctrine()->getRepository(View::class)->find($request->get('id'));
        $course = $page->getCourses();

        $entityManager->remove($page);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'le commentaire selectionné a été supprimé!!!'
        );
        return $this->redirectToRoute('uv_details',['id' => $course->getId()]);

    }

}