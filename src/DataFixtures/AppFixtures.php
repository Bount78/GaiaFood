<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setLastName('Smith');
        $user->setFirstName('John');
        $user->setEmail('john.smith@mail.com');
        $user->setPassword('password123!');

        $manager->persist($user);

        $manager->flush();
    }
}

// public function load(ObjectManager $manager): void
// {
//     $user = new Utilisateur();
//     $user->setNom('Dupont');
//     $user->setPrenom('Jean');
//     $user->setEmail('jean.dupont@mail.com');
//     $user->setMotDePasse('password123');

//     $manager->persist($user);

//     $manager->flush();
// }