<?php

namespace App\Controller;


use App\Service\SortieUpdate;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="main_")
 */

class MainController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function home(SortieUpdate $sortieUpdate, EntityManagerInterface $entityManager): Response
    {

        $sortieUpdate->updateSorties($entityManager);

        if($this->getUser())
        {

            return $this->redirectToRoute('sortie_index');
        }
        return $this->redirectToRoute('app_login');
    }
}
