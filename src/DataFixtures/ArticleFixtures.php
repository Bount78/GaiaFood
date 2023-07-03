<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Article;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Vous pouvez avoir besoin d'ajouter une fixture pour User si vous n'en avez pas déjà une

        // Récupérer un utilisateur pour être l'auteur des articles
        // Dans cet exemple, je récupère le premier utilisateur, mais vous pouvez modifier cela en fonction de vos besoins
        $user = $manager->getRepository(User::class)->findOneBy([]);

        if (!$user) {
            throw new \Exception('No users found, please add some User fixtures');
        }

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitle('Titre de l\'article ' . $i);
            $article->setContent('Contenu de l\'article ' . $i);
            $article->setCreatedAt(new DateTimeImmutable());
            $article->setUser($user);

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
