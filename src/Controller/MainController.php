<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="main_")
 */

class MainController extends AbstractController
{
    /**
     * @Route("accueil/deconecte", name="")
     */
    public function accueilDeconnecte(): Response
    {


        return $this->render('main/accueil/deconecte.html.twig', [

        ]);
    }
    /**
     * @Route("accueil/conecte", name="")
     */
    public function accueilConnecte(): Response
    {


        return $this->render('main/accueil/conecte.html.twig', [

        ]);
    }
}
