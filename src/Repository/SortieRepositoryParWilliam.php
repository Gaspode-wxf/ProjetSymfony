<?php

namespace App\Repository;

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
class SortieRepositoryParWilliam extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function listeSortieOuvertes(EtatRepository $etatRepository, Participant $participant)
    {


        return $this->createQueryBuilder('sortie')
            ->andWhere('sortie.etat = :val1 or sortie.etat = :val2 or sortie.etat = :val3 or (sortie.etat = :val4 and sortie.organisateur = :val5) ')
            ->setParameter('val1', $etatRepository->findOneBy(['libelle'=>'Ouverte']))
            ->setParameter('val2', $etatRepository->findOneBy(['libelle'=>'Activité en Cours']))
            ->setParameter('val3', $etatRepository->findOneBy(['libelle'=>'Clôturée']))
            ->setParameter('val4', $etatRepository->findOneBy(['libelle'=>'En création']))
            ->setParameter('val5', $participant->getId())
            ->orderBy('sortie.id', 'ASC')

            ->getQuery()
            ->getResult()
            ;
    }




public function listeSortieParEtat(Etat $etat)
{
    return $this->createQueryBuilder('sortie')
        ->andWhere('sortie.etat = :val')
        ->setParameter('val',$etat)
        ->orderBy('sortie.id', 'ASC')

        ->getQuery()
        ->getResult()
        ;
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
