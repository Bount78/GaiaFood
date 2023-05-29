<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {

        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }


        return $this->render('category/index.html.twig', [
            'title_page' => 'Les catégories',
        ]);
    }

    

    #[Route('/category/{id}', name: 'category_show')]
    public function show(Category $category, CategoryRepository $categoryRepository)
    {

        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }
        $categories = $categoryRepository->findAll();


        return $this->render('category/show.html.twig', [
            'category' => $category,
            'title_page' => 'La catégorie',
            'categories' => $categories,
        ]);
    }

    #[Route('/navBarCat', name: 'app_cat_navbar')]
    public function CatNavbar(CategoryRepository $categoryRepository): Response
    {
        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }
        $categories = $categoryRepository->findAll();

        return $this->render('component/navbar.html.twig', [
            'categories' => $categories,
        ]);
    }
}
