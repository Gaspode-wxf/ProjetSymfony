<?php

namespace App\Repository;

use App\Data\rechercheData;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
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
    public function rechercher(rechercheData $recherche, Participant $participant)
    {

        $query = $this
            ->createQueryBuilder('sortie');
        if (!empty($recherche->champRecherche)) {
            $query = $query
                ->andWhere('sortie.nom LIKE :recherche')
                ->setParameter('recherche', '%'.$recherche->champRecherche.'%');}
        if (!empty($recherche->orga)) {
          $query->andWhere('sortie.organisateur = :participant')
              ->setParameter('participant', $participant->getId());
        }

      //  if (!empty($recherche->inscrit))
       // {
         //   $query = $this->getEntityManager();

           // $query->andWhere('sortie.participants  IN :sortie.id')
            //->setParameter('participant', $participant->getId());
       // }
      //  if (!empty($recherche->pasInscrit))
       //{
         //   $query->andWhere('sortie.participants = false');

        //}

        if (!empty($recherche->campus))
        {
            $query->andWhere('sortie.siteOrganisateur = :campus')
                ->setParameter('campus', $recherche->campus);
        }
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
