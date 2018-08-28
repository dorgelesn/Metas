<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Course;


define("PRATICAL",     "pratical");

class UserController extends AbstractController
{
    const PRATICAL  = "pratical";
    const GOODPLAN  = "goodplan";

    /**
    * @Route("/user/space", name="space")
    */
    public function space()
    {

        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();
        $info  = array();
        $goodPlan= array();


        foreach ($pages as $key)
        {
            // check if the page is a pratical information or a good plan
            if($key->getCategory()->getLibelle()== self::PRATICAL)
            {
                array_push($info,$pages);

            }else
            {
                array_push($goodPlan,$pages);
            }
        }

        if(!$courses)
            throw $this->createNotFoundException('No Courses in the current database');

        return $this->render('pages/user/userspace.html.twig',['courses' => $courses,'praticals'=>$info,'goodplans'=>$goodPlan]);
    }
    


    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper): Response
    {

        return $this->render('pages/authentification.html.twig', [
            // dernier username saisi (si il y en a un)
            'last_username' => $helper->getLastUsername(),
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }
    /**
     * La route pour se deconnecter.
     *
     * Mais celle ci ne doit jamais être executé car symfony l'interceptera avant.
     *
     *
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
    
} 