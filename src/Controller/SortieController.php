<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->listeSortiesOuvertes(),
        ]);
    }


    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $sortie->setOrganisateur($this->getUser());
            $sortie->setSiteOrganisateur($user->getCampus());
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET", "POST"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig',[
            'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/inscription/{id}", name="inscription", methods={"POST"})
     */
    public function inscriptionSortie(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('inscription' . $sortie->getId(), $request->request->get('_token'))) {
            $participant = $this->getUser();
            $sortie->addParticipant($participant);
            $entityManager->persist($sortie);
            $entityManager->persist($participant);
            $entityManager->flush();
        }


        return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/desinscription/{id}", name="desinscription", methods={"POST"})
     */
    public function desinscriptionSortie(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('desinscription' . $sortie->getId(), $request->request->get('_token'))) {
            $participant = $this->getUser();


            $sortie->removeParticipant($participant);
            $entityManager->persist($sortie);
            $entityManager->persist($participant);
            $entityManager->flush();
        }


        return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/annuler/{id}", name="annuler", methods={"POST"})
     */
    public function annulerSortie(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('annuler' . $sortie->getId(), $request->request->get('_token'))) {

            // solution temporaire pour récupérer l'etat.
            // TODO ajouter une methode dans le Repo Etat pour trouver un état par libelle
            $etats = $entityManager->getRepository('App:Etat')->findAll();




            $sortie->setEtat($etats[4]);
            $entityManager->persist($sortie);

            $entityManager->flush();
        }


        return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
    }

        /**
         * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
         */
        public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
        {
            $form = $this->createForm(SortieType::class, $sortie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('sortie/edit.html.twig', [
                'sortie' => $sortie,
                'form' => $form,
            ]);
        }
        /**
         * @Route("/testons/{id}", name="testons")
         */
        public function testons(Request $request):Response
    {

      if( $request->get('orga')){
          dd('ca marche');
      }

        return $this->redirectToRoute('sortie_index');

    }


}
