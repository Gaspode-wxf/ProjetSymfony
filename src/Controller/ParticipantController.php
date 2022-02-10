<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/participant")
 */
class ParticipantController extends AbstractController
{


/*
 *
 * Personne ne peut créer de participants. Methode en commentaires pour archive

     * @Route("/new", name="participant_new", methods={"GET", "POST"})

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->redirectToRoute('participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/new.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }*/


    //accessible à tous les user connectés

    /**
     * @Route("/{id}", name="participant_show", methods={"GET"})
     */
    public function show(Participant $participant): Response
    {
        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
        ]);
    }




    //accessible uniquement par l'utilisateur en question

    /**
     * @Route("/{id}/modifier", name="participant_modifier", methods={"GET", "POST"})
     */
    public function modifier(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        if ($participant->getId() != $this->getUser()->getId())
        {
            return $this->redirectToRoute('sortie_index');
        }else

        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('participant_index', [], Response::HTTP_SEE_OTHER);
        }



            return $this->renderForm('participant/modifierSonProfil.html.twig', [
                'participant' => $participant,
                'form' => $form,

            ]);


    }

}
