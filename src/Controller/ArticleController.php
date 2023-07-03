<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {

        // Vérifier si l'utilisateur est identifié
        // $current_user = $this->getUser();
        // if (!$current_user) {
        //     return $this->redirectToRoute('app_logout');
        // }

        $catNavbar = $categoryRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'categories' => $catNavbar,
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {

        // Vérifier si l'utilisateur est identifié
        // $current_user = $this->getUser();
        // if (!$current_user) {
        //     return $this->redirectToRoute('app_logout');
        // }

        $catNavbar = $categoryRepository->findAll();
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new DateTimeImmutable());
            $articleRepository->save($article, true);



            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'categories' => $catNavbar,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article, 
    CategoryRepository $categoryRepository, 
    Request $request, 
    int $id, 
    EntityManagerInterface $entityManager, 
    PaginatorInterface $paginator, 
    CommentRepository $commentRepository,
    ArticleRepository $articleRepository): Response
    {

        // Vérifier si l'utilisateur est identifié
        // $current_user = $this->getUser();
        // if (!$current_user) {
        //     return $this->redirectToRoute('app_logout');
        // }

        $categories = $categoryRepository->findAll();
        $article = $articleRepository->find($id);


        // Créer une instance de l'entité Commentaire
        $commentaire = new Comment();
        $commentaire->setFkUser($this->getUser()); // Associer l'utilisateur connecté comme auteur du commentaire
        // Créer le formulaire de commentaire
        $form = $this->createForm(CommentType::class, $commentaire);
        $form->handleRequest($request);

        $commentaires = $commentRepository->findBy(['article' => $article], ['CreatedAt' => 'DESC']);

        $commentaires = $paginator->paginate(
            $commentaires, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le commentaire à la recette ou à l'article
            // Enregistrer le commentaire dans la base de données
            $commentaire = $form->getData();
            $commentaire->setCreatedAt(new \DateTimeImmutable()); 
            $article->addComment($commentaire);
            $commentaire->setArticle($article);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté ! ');
            // Rediriger vers la page des commentaires avec un message de confirmation
            return $this->redirectToRoute('app_article_show', ['id' => $id]);
        }


        return $this->render('article/show.html.twig', [
            'article' => $article,
            'categories' => $categories,
            'comments' => $article->getComments(),
            'form' => $form->createView(),
            'comments' => $commentaires,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        
        // Vérifier si l'utilisateur est identifié
        // $current_user = $this->getUser();
        // if (!$current_user) {
        //     return $this->redirectToRoute('app_logout');
        // }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
