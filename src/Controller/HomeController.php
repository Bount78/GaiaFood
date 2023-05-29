<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository, ArticleRepository $articleRepository): Response
    {

        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }

        $categories = $categoryRepository->findAll();
        $recentArticles = $articleRepository->findRecentArticles(5);
        

        if ($current_user) {
            return $this->render('home/index.html.twig', [
                'title_page' => 'Tableau de Bord',
                'name' => $current_user->getFirstName(),
                'categories' => $categories,
                'recentArticles' => $recentArticles,
            ]);
        }
    
        return $this->redirectToRoute('app_login');
    }
}
