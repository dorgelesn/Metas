<?php
/*
 * Status : 5the search function is not finish
*/
namespace App\Controller;

use App\Entity\Course;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UvController extends AbstractController
{
    /**
    * @Route("/contribution/adduv", name="addUV")
    */
    public function add()
    {
       
        return $this->render('pages/uv/addUV.html.twig');
    }



    public function add_check(Request $request, SessionInterface $session)
    {

        $code = $request->get('_code');
        $title = $request->get('_label');
        $description = $request->get('_description');

       if(!is_null($code) && !is_null($title) && !is_null($description))
       {

          $entityManager = $this->getDoctrine()->getManager();

          $course = new Course();
          $course->setCode($code);
          $course->setTitre($title);
          $course->setDescription($description);

          $entityManager->persist($course);
          $entityManager->flush();

           $this->addFlash(
               'notice',
               'Nouvelle UV ajoutée'
           );
           return $this->redirectToRoute('space');
       }

    }

    public function mod_check(Request $request)
    {
        $titre = $request->get('_label');
        $code = $request->get('_code');
        $description = $request->get('_description');

        $course = $this->getDoctrine()->getRepository(Course::class)->find($request->get('id'));
        if (!$course) {
            throw $this->createNotFoundException(
                'No course found for id '.$request->get('id')
            );
        }

        $entityManager = $this->getDoctrine()->getManager();

       
        $course->setCode($code);
        $course->setTitre($titre);
        $course->setDescription($description);


        $entityManager->flush();

            $this->addFlash(
                'notice',
                'le cour a été modifié!'
            );
            return $this->redirectToRoute('space');


    }
    /**
     * @Route("/uv/mod", name="uv_mod")
     */
    public function modify(Request $request)
    {

        $course = $this->getDoctrine()->getRepository(Course::class)->find($request->get('id'));

        if(!is_null($course))
        {

            return $this->render('pages/uv/addUV.html.twig',array('course'=>$course));

        }else
            return $this->render('pages/404.html.twig');

    }

    /**
     * @Route("/uv/search", name="uv_search")
     */

    public function search(Request $request)
    {


            $courses = $this->getDoctrine()->getRepository(Course::class)->findByTitre($request->get('keyword'));
            var_dump($courses);
            die();

        return $this->render('pages/user/userspace.html.twig',array('courses' => $courses));


    }

    /**
     * @Route("/uv/details", name="uv_details")
     */

    public function details(Request $request)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($request->get('id'));


        return $this->render('pages/custom/details.html.twig',array('course' => $course));
    }

    /**
     * @Route("/uv/delete", name="uv_delete")
     */

    public function delete(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $course = $this->getDoctrine()->getRepository(Course::class)->find($request->get('id'));


        $entityManager->remove($course);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Le cours selectionné a été supprimé!!!'
        );
        return $this->redirectToRoute('space');
    }

}