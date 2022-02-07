<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="home_")
 */

class MainController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {


        return $this->render('home/home.html.twig', [

        ]);
    }
}