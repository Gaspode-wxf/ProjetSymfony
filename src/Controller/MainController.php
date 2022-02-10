<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="main_")
 */

class MainController extends AbstractController
{
    /**
     * @Route("", name="accueil_deconecte")
     */
    public function accueilDeconnecte(): Response
    {
        return $this->redirectToRoute('app_login');
    }
    /**
     * @Route("accueil/conecte", name="accueil_conecte")
     */
    public function accueilConnecte(): Response
    {
        return $this->redirectToRoute('sortie_index');
    }
}
