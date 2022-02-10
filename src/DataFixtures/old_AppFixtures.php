<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Sortie;
use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Participant;
use Faker;

class old_AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 1; $i++) {
            $campus[$i] = new campus();
            $campus[0]->setNom('SAINT-HERBLAIN');
            $manager->persist($campus[$i]);
        }


        // Création de 50 participants
        $participant = [];

        for ($i = 0; $i < 20; $i++) {
            $participant[$i] = new Participant();
            $participant[$i]->setPrenom($faker->firstName);
            $participant[$i]->setNom($faker->lastName);
            $participant[$i]->setTelephone("06XXXXXXXX");
            $participant[$i]->setEmail($faker->email);
            $participant[$i]->setPseudo($faker->userName);
            $participant[$i]->setPassword($faker->password);
            $participant[$i]->setActif(true);
            $participant[$i]->setCampus($campus[0]);
            $manager->persist($participant[$i]);
        }
        for ($i = 0; $i < 1; $i++) {
            $campus[$i] = new campus();
            $campus[0]->setNom('CHARTRES DE BRETAGNE');
            $manager->persist($campus[$i]);
        }


        // Création de 50 participants
        $participant = [];

        for ($i = 0; $i < 20; $i++) {
            $participant[$i] = new Participant();
            $participant[$i]->setPrenom($faker->firstName);
            $participant[$i]->setNom($faker->lastName);
            $participant[$i]->setTelephone("06XXXXXXXX");
            $participant[$i]->setEmail($faker->email);
            $participant[$i]->setPseudo($faker->userName);
            $participant[$i]->setPassword($faker->password);
            $participant[$i]->setActif(true);
            $participant[$i]->setCampus($campus[0]);
            $manager->persist($participant[$i]);
        }
        for ($i = 0; $i < 1; $i++) {
            $campus[$i] = new campus();
            $campus[0]->setNom('LA ROCHE SUR YON');
            $manager->persist($campus[$i]);
        }


        // Création de 50 participants
        $participant = [];

        for ($i = 0; $i < 20; $i++) {
            $participant[$i] = new Participant();
            $participant[$i]->setPrenom($faker->firstName);
            $participant[$i]->setNom($faker->lastName);
            $participant[$i]->setTelephone("06XXXXXXXX");
            $participant[$i]->setEmail($faker->email);
            $participant[$i]->setPseudo($faker->userName);
            $participant[$i]->setPassword($faker->password);
            $participant[$i]->setActif(true);
            $participant[$i]->setCampus($campus[0]);
            $manager->persist($participant[$i]);
        }

        $manager->flush();

        // Création de 4 états
        $libelles = ['En création', 'Ouverte', 'Clôturée', 'Activité en cours', 'Annulée', 'Activité terminée', 'Activité historisée'];

        $etat = [];

        for ($i = 0; $i < count($libelles); $i++) {
            $etat[$i] = new Etat();
            $etat[$i]->setLibelle($libelles[$i]);
            $manager->persist($etat[$i]);
        }

        // Création de 20 villes
        $ville = [];

        for ($i = 0; $i < 20; $i++) {
            $ville[$i] = new Ville();
            $ville[$i]->setNom($faker->city());
            $ville[$i]->setCodePostal("XXXXX");
            $manager->persist($ville[$i]);
        }

        // Création de 30 lieux
        $lieu = [];

        for ($i = 0; $i < 30; $i++) {
            $lieu[$i] = new Lieu();
            $lieu[$i]->setNom($faker->city);
            $lieu[$i]->setRue($faker->streetName);
            $lieu[$i]->setLatitute($faker->latitude($min = -90, $max = 90));
            $lieu[$i]->setLongitude($faker->longitude($min = -180, $max = 180));
            $lieu[$i]->setVille($ville[$faker->numberBetween($min = 0, $max = count($ville) - 1)]);
            $manager->persist($lieu[$i]);
        }
        $sortie = [];

        for ($i = 0; $i < 20; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '- 50 days', $interval = '+50 day', $timezone = null));
            $sortie[$i]->setDuree($faker->numberBetween($min = 1, $max = 8));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setnbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setsiteOrganisateur($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setEtat($etat[0]);
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getnbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
        }
        $sortie = [];

        for ($i = 0; $i < 20; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '- 50 days', $interval = '+50 day', $timezone = null));
            $sortie[$i]->setDuree($faker->numberBetween($min = 1, $max = 8));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setnbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setsiteOrganisateur($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setEtat($etat[1]);
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getnbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
        }
        $sortie = [];

        for ($i = 0; $i < 20; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '- 50 days', $interval = '+50 day', $timezone = null));
            $sortie[$i]->setDuree($faker->numberBetween($min = 1, $max = 8));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setnbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setsiteOrganisateur($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setEtat($etat[2]);
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getnbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
        }
        $sortie = [];

        for ($i = 0; $i < 20; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '- 50 days', $interval = '+50 day', $timezone = null));
            $sortie[$i]->setDuree($faker->numberBetween($min = 1, $max = 8));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setnbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setsiteOrganisateur($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setEtat($etat[3]);
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getnbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
        }
        $sortie = [];

        for ($i = 0; $i < 20; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '- 50 days', $interval = '+50 day', $timezone = null));
            $sortie[$i]->setDuree($faker->numberBetween($min = 1, $max = 8));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setnbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setsiteOrganisateur($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setEtat($etat[4]);
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getnbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
        }
        $sortie = [];

        for ($i = 0; $i < 20; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '- 50 days', $interval = '+50 day', $timezone = null));
            $sortie[$i]->setDuree($faker->numberBetween($min = 1, $max = 8));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setnbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setsiteOrganisateur($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setEtat($etat[5]);
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getnbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
        }
        $sortie = [];

        for ($i = 0; $i < 20; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '- 50 days', $interval = '+50 day', $timezone = null));
            $sortie[$i]->setDuree($faker->numberBetween($min = 1, $max = 8));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setnbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setsiteOrganisateur($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setEtat($etat[6]);
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getnbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
        }

        $manager->flush();
    }
}
