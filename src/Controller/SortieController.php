<?php

namespace App\Controller;

use App\Data\rechercheData;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\FiltresSortiesType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\SortieRepositoryParWilliam;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(SortieRepositoryParWilliam $sortieRepository,
                          EtatRepository $etatRepository,
                          EntityManagerInterface $entityManager,
                          Request $request): Response
    {
        $data = new rechercheData();
        $data->dateMin = new \DateTime();
        $form = $this->createForm(FiltresSortiesType::class, $data);
        $form->handleRequest($request);
        $entityManager->initializeObject($this->getUser()->getSorties());
        $listeSortie = $this->getUser()->getSorties();
        $sorties = $entityManager->getRepository('App:Sortie')->rechercher($data, $this->getUser(), $listeSortie);

      //  return $this->render('sortie/index.html.twig', [
        //    'sorties' => $sorties,
          //  'form' => $form->createView(),

        //]);}

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
             'form'=> $form->createView(),

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
            $sortie->getEtat()->addSorty($sortie);
            $entityManager->persist($sortie->getEtat());
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
            if ($sortie->getParticipants()->count()==$sortie->getNbInscriptionsMax())
            {
                $cloture = $entityManager->getRepository('App:Etat')->findOneBy(['libelle'=>'Clôturée']);
                $sortie->setEtat($cloture);
            }
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
            $ouverture = $entityManager->getRepository('App:Etat')->findOneBy(['libelle'=>'Ouverte']);
            $sortie->setEtat($ouverture);
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
        public function edit(Request $request,
                             Sortie $sortie,
                             EntityManagerInterface $entityManager
                            ): Response
        {
            $entityManager->initializeObject($sortie->getEtat());
            $enCrea = $entityManager->getRepository('App:Etat')->findOneBy(['libelle'=>'En création']);

           $etatSortie = $sortie->getEtat();
$entityManager->persist($etatSortie);
$entityManager->persist($enCrea);



           if ($sortie->getOrganisateur()->getId() != $this->getUser()->getId() or
           $etatSortie!=$enCrea)
           {


            throw $this->createAccessDeniedException();

            }else
               $form = $this->createForm(SortieType::class, $sortie);
               $form->handleRequest($request);




            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->flush();

                $this->addFlash('succes', 'La sortie a bien été modifiée');

                return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
            }



            return $this->renderForm('sortie/edit.html.twig', [
                'sortie' => $sortie,
                'form' => $form,
            ]);
        }


}
