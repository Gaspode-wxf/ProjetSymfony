<?php

namespace App\Service;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;

class SortieUpdate
{

    private $etats;

    private $sortiesCloturees;
    private $sortiesEnCours;
    private $repoEtat;
    private $repoSortie;



    public function __construct(EtatRepository $etatRepository, SortieRepository $sortieRepository)
    {
        //affectation des repo
        $this->repoSortie=$sortieRepository;
        $this->repoEtat=$etatRepository;

        $this->etats=$this->repoEtat->findAll();


        ;
    }


    public function ajouterHeures(int $val, \DateTimeInterface $dateTime):\DateTime
    {
       return (clone $dateTime)->add(new \DateInterval("PT".$val."H"));

    }

    public function moisProchain(\DateTimeInterface $dateTime):\DateTime
    {

        return (clone $dateTime)->add(new \DateInterval("P1M"));
    }



    public function historiserSorties(EntityManagerInterface $entityManager):void
    {
        $sorties=$this->repoSortie->findAll();
        //Générer la date du jour
        $now = new \DateTime();
        // Historiser les sorties de plus d'un mois
        foreach ($sorties as $sortie)
        {
            if ($sortie->getEtat()!==$this->repoEtat->findOneBy(['libelle'=>'Activité historisée']) and ( ($now > $this->moisProchain($sortie->getDateHeureDebut()))) )

            {
                $sortie->setEtat($this->repoEtat->findOneBy(['libelle'=>'Activité historisée']));
                $entityManager->persist($sortie);
            }

        }
        $entityManager->flush();


    }

    public function cloturerSorties(EntityManagerInterface $entityManager):void
    {
        //Générer la date du jour
        $now = new \DateTime();

        $sortiesOuvertes = $this->repoSortie->findBy(['etat'=>$this->repoEtat->findOneBy(['libelle'=>'Ouverte'])]);
        foreach ($sortiesOuvertes as $ouverte)
        {
            if ($now>=$ouverte->getDateLimiteInscription())
            {

                $ouverte->setEtat($this->repoEtat->findOneBy(['libelle'=>'Clôturée']));

                $entityManager->persist($ouverte);
            }
        }
        $entityManager->flush();
    }

    public function updateVersEnCoursEtTerminees(EntityManagerInterface $entityManager):void
    {
        //Générer la date du jour
        $now = new \DateTime();

        $sortiesOuvertes = $this->repoSortie->findBy(['etat'=>$this->repoEtat->findOneBy(['libelle'=>'Ouverte'])]);

        $sortiesCloturees = $this->repoSortie->findBy(['etat'=>$this->repoEtat->findOneBy(['libelle'=>'Clôturée'])]);

        $sortiesOuvrables  = array_merge($sortiesOuvertes,$sortiesCloturees);



        foreach ($sortiesOuvrables as $ouvrable)
        {
            if ($ouvrable->getDateHeureDebut()<=$now and $now <= $this->ajouterHeures($ouvrable->getDuree(),$ouvrable->getDateHeureDebut()))
            {
                $ouvrable->setEtat($this->repoEtat->findOneBy(['libelle'=>'Activité en Cours']));
                $entityManager->persist($ouvrable);
            }
            elseif ($this->ajouterHeures($ouvrable->getDuree(),$ouvrable->getDateHeureDebut())<$now)
            {


                $ouvrable->setEtat($this->repoEtat->findOneBy(['libelle'=>'Activité terminée']));
                $entityManager->persist($ouvrable);
            }
        }
        $entityManager->flush();

    }

    public function terminerSortieEnCours(EntityManagerInterface $entityManager):void
    {
        //Générer la date du jour
        $now = new \DateTime();

        $sortiesEnCours = $this->repoSortie->findBy(['etat'=>$this->repoEtat->findOneBy(['libelle'=>'Activité en cours'])]);

        foreach ($sortiesEnCours as $enCour)
        {
            if ($this->ajouterHeures($enCour->getDuree(), $enCour->getDateHeureDebut())<$now)
            {
                $enCour->setEtat($this->repoEtat->findOneBy(['libelle'=>'Activité terminée']));
                $entityManager->persist($enCour);
            }
        }

        $entityManager->flush();

    }

    public function historiserSortiesEnCreation(EntityManagerInterface $entityManager):void
    {
        $now = new \DateTime();

        $sortiesEnCreation = $this->repoSortie->findBy(['etat'=>$this->repoEtat->findOneBy(['libelle'=>'En création'])]);

        foreach ($sortiesEnCreation as $enCrea)
        {
            if ($now > $enCrea->getDateHeureDebut())
            {

                $enCrea->setEtat($this->repoEtat->findOneBy(['libelle'=>'Activité historisée']));
                $entityManager->persist($enCrea);
            }
        }
        $entityManager->flush();
    }


    public function updateSorties(EntityManagerInterface $entityManager):bool
    {
        $this->historiserSorties($entityManager);
         $this->terminerSortieEnCours($entityManager);
         $this->updateVersEnCoursEtTerminees($entityManager);
        $this->cloturerSorties($entityManager);
        $this->historiserSortiesEnCreation($entityManager);
        return true;
    }


}