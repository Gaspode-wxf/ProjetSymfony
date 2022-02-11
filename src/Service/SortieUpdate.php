<?php

namespace App\Service;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;

class SortieUpdate
{

    private $etats;
    private $sorties;
    private $sortiesOuvertes;
    private $repoEtat;
    private $repoSortie;


    public function __construct(EtatRepository $etatRepository, SortieRepository $sortieRepository)
    {
        $this->repoSortie=$sortieRepository;
        $this->repoEtat=$etatRepository;
        $this->etats=$this->repoEtat->findAll();
        $this->sorties=$this->repoSortie->findAll();
    }

    public function updateSorties(EntityManagerInterface $entityManager):void
    {
        //etape 1 chercher les sorties qui sont Ouvertes
        $etatOuvert = $this->repoEtat->findOneBy(['libelle'=>'Ouverte']);
        $sortiesOuvertes = $this->repoSortie->findBy(['etat'=>$etatOuvert]);
        $sortiesPerimees = [];

        //etape 2 générer la date du jour
        $now = new \DateTime();

        //etape 3 comparer chaque date d'une sortie ouverte avec la date du jour
        foreach ($sortiesOuvertes as $sortie)
        {
            if ($sortie->getDateHeureDebut() < $now)
            {
               array_push($sortiesPerimees,$sortie);
            }

            foreach ($sortiesPerimees as $perimee)
            {
                $etatTerminee = $this->repoEtat->findOneBy(['libelle'=>'Activité terminée']);
                $perimee->setEtat($etatTerminee);

                $entityManager->persist($perimee);



            }
            $entityManager->flush();

        }

    }

}