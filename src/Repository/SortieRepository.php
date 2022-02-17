<?php

namespace App\Repository;

use App\Data\rechercheData;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $etatRepository;

    public function __construct(ManagerRegistry $registry, EtatRepository $etatRepository)
    {
        parent::__construct($registry, Sortie::class);
        $this->etatRepository=$etatRepository;
    }

     /**
      * @return Sortie[] Returns an array of Sortie objects
      */

    public function listeSortiesOuvertes()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.etat = 5')
            ->orWhere('s.etat = 4')
            ->orWhere('s.etat = 3')
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

public function listeSortieParEtat(Etat $etat)
{
    return $this->createQueryBuilder('s')
        ->andWhere('s.etat = $etat')
        ->orderBy('s.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
        ;
}

public function listeParSelectionEtat(string $listeEtat = ''){
    //traiter un formulaire vide
    if($listeEtat!==''){
        //on récupère la chaine de carractère d'un formulaire de filtrage

        $tableauSelection = explode(',', $listeEtat);


        //on instancie une nouvelle requette
        $request = $this->createQueryBuilder('sortie');
        foreach ($tableauSelection as $selection) {
            $request->andWhere('sortie.etat = :val')->setParameter('val', $selection);
        }
        return $request->orderBy('sortie.id','ASC')->getQuery()->getResult();
    }

        else return [] ;
    }
    public function rechercher(rechercheData $recherche, Participant $participant, $sortiesParticipant)
    {

        $query = $this
            ->createQueryBuilder('sortie')
            ->andWhere('sortie.etat !=  :val1')
            ->setParameter('val1', $this->etatRepository->findOneBy(['libelle'=>'Annulée']))
            ->andWhere('sortie.etat !=  :val2')
            ->setParameter('val2', $this->etatRepository->findOneBy(['libelle'=>'Activité historisée']));

        if (!empty($recherche->champRecherche)) {
            $query = $query
                ->andWhere('sortie.nom LIKE :recherche')
                ->setParameter('recherche', '%'.$recherche->champRecherche.'%');}

        if (!empty($recherche->campus))
        {
            $query->andWhere('sortie.siteOrganisateur = :campus')
                ->setParameter('campus', $recherche->campus);
        }

        if($recherche->dateMin)
            $query->andWhere('sortie.dateHeureDebut >= :dateMin')
            ->setParameter('dateMin', $recherche->dateMin);

        if ($recherche->dateMax)
        {
            $query->andWhere('sortie.dateHeureDebut < :dateMax')
                ->setParameter('dateMax', $recherche->dateMax);
        }

        if (!$recherche->perime)
        {
            $query->andWhere('sortie.etat !=  :val3')
            ->setParameter('val3', $this->etatRepository->findOneBy(['libelle'=>'Activité terminée']));
        }

        if ($recherche->inscrit and !$recherche->pasInscrit)
        {
            foreach ($participant->getSorties() as $sortie)
            {
                $query->andWhere('sortie.id = '.$sortie->getId());
            }
          //  dd(['recherche'=>$recherche, 'requete'=>$query->getQuery(), 'resultats'=>$query->getQuery()->getResult()]);
        }

        if ($recherche->pasInscrit and !$recherche->inscrit)
        {
            foreach ($participant->getSorties() as $sortie)
            {
                $query->andWhere('sortie.id != '.$sortie->getId());
            }
        }

        if ($recherche->perime)
        {
            $query->leftJoin(
                'sortie.etat',
                'etat',
                Join::WITH,
                'etat ='.$this->etatRepository->findOneBy(['libelle'=>'Activité terminée'])->getId()
            );

            /*$query->andWhere('sortie.etat = :val4 ')
                ->setParameter('val4', $this->etatRepository->findOneBy(['libelle'=>'Activité terminée']));*/
        }

        if ($recherche->orga)
        {


            $query->leftJoin('sortie.organisateur','organisateur',Join::WITH,'organisateur ='.$participant->getId());
            /*$query->andWhere('sortie.organisateur = :participant')
                ->setParameter('participant', $participant->getId());*/
        }


/*        if (!$recherche->orga and !$recherche->inscrit and !$recherche->pasInscrit and !$recherche->perime)
        {
            dd(['recherche'=>$recherche, 'requete'=>$query->getQuery(), 'resultats'=>$query->getQuery()->getResult()]);

        }

        if ($recherche->orga and $recherche->inscrit and $recherche->pasInscrit and $recherche->perime)
        {
            dd(['recherche'=>$recherche, 'requete'=>$query->getQuery(), 'resultats'=>$query->getQuery()->getResult()]);

        }*/



/*       dd(['recherche'=>$recherche, 'requete'=>$query->getQuery(), 'resultats'=>$query->getQuery()->getResult()]);*/
        return $query->getQuery()->getResult();

    }



    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
