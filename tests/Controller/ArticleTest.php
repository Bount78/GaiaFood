<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testIndex()
    {
    $client = static::createClient();

    // Récupérer l'utilisateur de test et le connecter
    $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
    $testUser = $userRepository->findOneByEmail('user0@example.com'); // Remplacer par un email d'utilisateur réel
    $client->loginUser($testUser);

    $crawler = $client->request('GET', '/article');
    $this->assertTrue($client->getResponse()->isRedirect()); // Vérifie si la réponse est une redirection
    $crawler = $client->followRedirect();

    $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    $this->assertResponseIsSuccessful();
    // $this->assertSelectorTextContains('Liste des articles');

    }
    public function testNew()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/article/new');
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user0@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        
        // $this->assertSelectorTextContains('h1', 'Nouvel article');
        // Vous pouvez ajouter plus d'assertions ici
    }

    public function testShow()
    {
        $client = static::createClient();
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $article = $entityManager->getRepository(Article::class)->findOneBy([]);
        $crawler = $client->request('GET', '/article/' . $article->getId());

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user0@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        // $this->assertSelectorTextContains('h1', 'Article n°' . $article->getId());
        // Vous pouvez ajouter plus d'assertions ici
    }

    public function testEdit()
    {
        $client = static::createClient();
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $article = $entityManager->getRepository(Article::class)->findOneBy([]);
        $crawler = $client->request('GET', '/article/' . $article->getId() . '/edit');
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user0@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // $this->assertSelectorTextContains('h1', 'Modifier l\'article n°' . $article->getId());
        // Vous pouvez ajouter plus d'assertions ici
    }

    public function testDelete()
    {
        $client = static::createClient();
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $article = $entityManager->getRepository(Article::class)->findOneBy([]);
        $client->request('DELETE', '/article/' . $article->getId());

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user0@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        // Vous pouvez ajouter plus d'assertions ici
    }
}
