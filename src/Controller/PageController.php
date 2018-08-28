<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    /**
    * @Route("/",name="accueil")
    */
    public function index()
    {
       
        return $this->render('pages/home.html.twig');
    }
    
     /**
    * @Route("/contact ")
    */
    public function contact()
    {
       
        return $this->render('pages/contact.html.twig');
    }
    
     /**
    * @Route("/actu ", name="actu")
    */
    public function news()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        return $this->render('pages/actu.html.twig',['events' => $events]);
    }
    
     /**
    * @Route("/partenaire",name="partenaire")
    */
    public function partner()
    {
       
        return $this->render('pages/partenaire.html.twig');
    }
    
    
     /**
    * @Route("/authentification ")
    */
    public function authen()
    {
       
        return $this->render('pages/authentification.html.twig');
    }
} 