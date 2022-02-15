<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */

class AdminController extends AbstractController
{


    /*
     * Route Admin qui donne accès à une liste de liens
     */


    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

/*
 *  Route pour accéder à l'édition et suppression des villes
 */

    /**
     * @Route("/ville", name="ville_index", methods={"GET"})
     */
    public function villeAdmin(VilleRepository $villeRepository): Response
    {
        return $this->render('ville/index.html.twig', [
            'villes' => $villeRepository->findAll(),
        ]);
    }

/**
 * @Route("/ville/{id}", name="ville_show", methods={"GET"})
 */
public function show(Ville $ville): Response
{
    return $this->render('ville/show.html.twig', [
        'ville' => $ville,
    ]);
}

/**
 * @Route("/ville/{id}/edit", name="ville_edit", methods={"GET", "POST"})
 */
public function edit(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(VilleType::class, $ville);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('ville_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('ville/edit.html.twig', [
        'ville' => $ville,
        'form' => $form,
    ]);
}

/**
 * @Route("/ville/{id}", name="ville_delete", methods={"POST"})
 */
public function delete(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
        $entityManager->remove($ville);
        $entityManager->flush();
    }

    return $this->redirectToRoute('ville_index', [], Response::HTTP_SEE_OTHER);
}

/*
 *  Routes pour Sortie
 */


    /*
     *  Permet à l'admin de voir l'intégralité des sorties sans aucun filtres
     */
    /**
     * @Route("/sortie", name="sortie", methods={"GET"})
     */
    public function sortieAdmin(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/admin.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    /*
     * Permet de supprimer une sortie
     */

    /**
     * @Route("/sortie/{id}", name="sortie_delete", methods={"POST"})
     */
    public function deleteSortie(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/sortie/{id}", name="annuler")
     */
    public function annulerSortie(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('annuler'.$sortie->getId(), $request->request->get('_token')))
        {
            $etat = $entityManager->getRepository('App:Etat')->findAll();
            $sortie->setEtat($etat[5]);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }
        return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
    }


    /*
 *  Routes pour Sortie
 */


    /*
     *  Permet à l'admin de voir l'intégralité des participants
     */



}
