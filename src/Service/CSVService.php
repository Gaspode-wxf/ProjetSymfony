<?php

namespace App\Service;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CSVService
{
    private UserPasswordHasherInterface $hasher;
    private EntityManagerInterface $entityManager;
    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager)
    {
        $this->hasher=$hasher;
        $this->entityManager=$entityManager;
    }

    public function readCSV($csvToRead):void
    {

        $serializer = new Serializer([new ObjectNormalizer()],[new CsvEncoder()]);
       $data = $serializer->decode(file_get_contents($csvToRead),'csv');
       $this->createUsers($data);

    }

    private function createUsers($data)
    {

        foreach ($data as $ligne)
        {

        $nom = $ligne['nom'];


            $user = new Participant();
            $tempPass = $this->hasher->hashPassword($user,'123456');
            $user->setNom($nom)
                ->setPrenom($ligne['prenom'])
                ->setPassword($tempPass)
                ->setTelephone($ligne['telephone'])
                ->setCampus($this->entityManager->getRepository('App:Campus')->find($ligne['campus_id']))
                ->setEmail($ligne['email'])
                ->setPseudo($ligne['email'])
                ->setActif(true);
            $this->entityManager->persist($user);

        }
        $this->entityManager->flush();
    }

}