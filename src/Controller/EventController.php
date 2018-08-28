<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;


class EventController extends Controller
{
    /**
     * @Route("/event/add", name="event")
     */
    public function add(Request $request)
    {
        // creates a task and gives it some dummy data for this example
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $event->getUrlTof();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // moves the file to the directory where images of event are stored
            $file->move(
                $this->getParameter('title_directory'),
                $fileName
            );

            $event->setUrlTof($fileName);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($event);
            $entityManager->flush();


            $this->addFlash(
                'notice',
                'Nouvel évenement ajouté'
            );

            return $this->redirectToRoute('actu');
        }


        return $this->render('pages/event/addEvent.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/event/mod", name="event_mod")
     */
    public function modify(Request $request)
    {



        // creates a task and gives it some dummy data for this example
        $event = $this->getDoctrine()->getRepository(Event::class)->find($request->get('id'));
         $file = new File($this->getParameter('get_directory').$event->getUrlTof());
        $event->setUrlTof($file);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $event->getUrlTof();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // moves the file to the directory where images of event are stored
            $file->move(
                $this->getParameter('title_directory'),
                $fileName
            );

            $event->setUrlTof($fileName);
            $entityManager = $this->getDoctrine()->getManager();


            $entityManager->flush();


            $this->addFlash(
                'notice',
                'Nouvel évenement ajouté'
            );

            return $this->redirectToRoute('actu');
        }


        return $this->render('pages/event/addEvent.html.twig', array(
            'form' => $form->createView(),
        ));

    }



    /**
     * @Route("/event/details", name="event_details")
     * @param Request $request in order to get id of event
     * @return \Symfony\Component\HttpFoundation\Response view with the corresponding event
     */

    public function details(Request $request)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($request->get('id'));


        return $this->render('pages/event/details.html.twig',array('event' => $event));
    }

    /**
     * @Route("/event/sup", name="event_delete")
     */

    public function delete(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $event = $this->getDoctrine()->getRepository(Event::class)->find($request->get('id'));


        $entityManager->remove($event);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'L\'évenement  selectionné a été supprimé!!!'
        );
        return $this->redirectToRoute('actu');
    }
}