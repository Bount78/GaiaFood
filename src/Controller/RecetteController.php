<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recette;
use App\Form\CommentType;
use App\Form\RecetteType;
use App\Entity\Evaluation;
use App\Entity\Ingredients;
use App\Form\EvaluationType;
use App\Util\ConversionUtil;
use App\Form\IngredientsType;
use PhpUnitsOfMeasure\UnitOfMeasure;
use App\Repository\CommentRepository;
use App\Repository\RecetteRepository;
use App\Repository\CategoryRepository;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use Symfony\Component\HttpFoundation\Request;
use PhpUnitsOfMeasure\PhysicalQuantity\Number;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpUnitsOfMeasure\Exception\NonNumericValue;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/recette')]
class RecetteController extends AbstractController
{
    #[Route('/', name: 'app_recette_index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository, CategoryRepository $categoryRepository): Response
    {
        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }

        $categories = $categoryRepository->findAll();

        $list = $recetteRepository->findAll();

        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecetteRepository $recetteRepository, SluggerInterface $slugger, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {

        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }

        $recette = new Recette();
        $formRecette = $this->createForm(RecetteType::class, $recette);

        $formRecette->handleRequest($request);
        
        $categories = $categoryRepository->findAll();
        
        if ($formRecette->isSubmitted() && $formRecette->isValid()) {

            $recetteRepository->save($recette, true);
            $file = $formRecette->get('image')->getData();


            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '.' . $file->guessExtension();

                // Stockez le fichier à l'emplacement souhaité
                $uploadDirectory = $this->getParameter('upload_directory') . '/recette';
                try {
                    $file->move($uploadDirectory, $newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception en conséquence, par exemple, afficher un message d'erreur ou effectuer une action spécifique
                }

                $recette->setImage($newFilename);
            }


            // Récupérez les catégories sélectionnées dans le formulaire

            $selectedCategories = $formRecette->get('categories')->getData();
            foreach ($selectedCategories as $category) {
                $recette->addCategory($category);
                $recette->addUser($current_user);
                $category->addFkRecette($recette);
            }

            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('recette/new.html.twig', [
            'recette' => $recette,
            'formRecette' => $formRecette,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_show', methods: ['GET', 'POST'])]
    public function show(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, EvaluationRepository $evaluationRepository, PaginatorInterface $paginator, CommentRepository $commentRepository): Response
    {

        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }

        // Récupération du dépôt Recette
        $recetteRepository = $entityManager->getRepository(Recette::class);
        $catNavbar = $categoryRepository->findAll();
        // Recherche de la recette spécifique par son ID
        $recette = $recetteRepository->find($id);
        $commentaires = $commentRepository->findBy(['fk_recette' => $recette], ['CreatedAt' => 'DESC']);

        dd($commentaires);



        $commentaires = $paginator->paginate(
            $commentaires, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        
        // Créer une instance de l'entité Commentaire
        $commentaire = new Comment();
        $commentaire->setFkUser($this->getUser()); // Associer l'utilisateur connecté comme auteur du commentaire
        // Créer le formulaire de commentaire
        $form = $this->createForm(CommentType::class, $commentaire);
        $form->handleRequest($request);
        //Form note d'évaluation
        $evaluation = new Evaluation();
        $formNote = $this->createForm(EvaluationType::class, $evaluation);
        
        if (!$recette) {
            throw $this->createNotFoundException(
                'Aucune recette trouvée pour l\'id ' . $id
            );
        }
        
        // Récupération des catégories associées à la recette
        $categories = $recette->getCategories();


        //Note Moyenne de la recette
        $repository = $entityManager->getRepository(Evaluation::class);
        $averageRating = $repository->getAverageRating($recette);
        $averageRatingFinal = round($averageRating, 1);




        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le commentaire à la recette ou à l'article
            // Enregistrer le commentaire dans la base de données
            $commentaire = $form->getData();
            $commentaire->setCreatedAt(new \DateTimeImmutable()); 
            $recette->addComment($commentaire);
            $commentaire->setFkRecette($recette);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté ! ');
            // Rediriger vers la page des commentaires avec un message de confirmation
            return $this->redirectToRoute('app_recette_show', ['id' => $id]);
        }

        $formNote->handleRequest($request);
        if ($formNote->isSubmitted() && $formNote->isValid()) {
            $evaluation = $formNote->getData();

            // Supprimer l'ancienne note de l'utilisateur pour cette recette
            $existingEvaluation = $evaluationRepository->findOneBy([
                'fk_user' => $current_user,
                'fk_recette' => $recette,
            ]);
            
            if ($existingEvaluation) {
                $entityManager->remove($existingEvaluation);
            }
            $evaluation->setFkUser($this->getUser());
            $evaluation->setFkRecette($recette);

            $entityManager->persist($evaluation);
            $entityManager->flush();

            $this->addFlash('success', 'Votre évaluation a été enregistrée.');
            $url = $request->headers->get('referer');
            return $this->redirect($url);
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'categories' => $categories,
            'category' => $catNavbar,
            'formNote' => $formNote->createView(),
            'form' => $form->createView(),
            'moyenne' => $averageRatingFinal,
            'comments' => $commentaires,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, RecetteRepository $recetteRepository, SluggerInterface $slugger, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {

        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }


        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        $categories = $categoryRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteRepository->save($recette, true);
            $file = $form->get('image')->getData();

            


            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '.' . $file->guessExtension();

                // Stockez le fichier à l'emplacement souhaité
                $uploadDirectory = $this->getParameter('upload_directory') . '/recette';
                try {
                    $file->move($uploadDirectory, $newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception en conséquence, par exemple, afficher un message d'erreur ou effectuer une action spécifique
                }

                // Enregistrez le nom du fichier dans votre entité Recette
                $recette->setImage($newFilename);

            }

            // Récupérez les catégories sélectionnées dans le formulaire

            $selectedCategories = $form->get('categories')->getData();
            foreach ($selectedCategories as $category) {
                $recette->addCategory($category);
                $category->addFkRecette($recette);
            }

            $entityManager->persist($recette);
            $entityManager->flush();

            $url = $request->headers->get('referer');
            return $this->redirect($url);
        }

        return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
            'categories' => $categories,
        ]);
    }


    #[Route('/delete/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recette->getId(), $request->request->get('_token'))) {
            $recetteRepository->remove($recette, true);
        }
    
        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }
    

    #[Route('/add-to-favorites/{id}', name: 'recette_add_to_favorites')]
    public function addToFavorites(Recette $recette, EntityManagerInterface $entityManager): Response
    {

        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }

        if (!$current_user->getFavoriteRecettes()->contains($recette)) {
            $current_user->addFavoriteRecette($recette);
            $entityManager->persist($current_user);
            $entityManager->flush();
        }
        // , ['id' => $recette->getId()] // rajouter a cote de la route show si bug
        $this->addFlash('success', 'La recette a été ajouté aux favoris !');
        return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()]);
    }

    #[Route('/remove-from-favorites/{id}', name: 'recette_remove_from_favorites')]
    public function removeFromFavorites(int $id, EntityManagerInterface $entityManager): Response
    {
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }

        // Récupérez la recette à partir de l'ID et supprimez-la des favoris de l'utilisateur
        $recette = $entityManager->getRepository(Recette::class)->find($id);

        if (!$recette) {
            throw $this->createNotFoundException('La recette n\'existe pas.');
        }

        $current_user->removeFavoriteRecette($recette);
        $entityManager->flush();

        $this->addFlash('success', 'La recette a été supprimée de vos favoris.');

        // Redirigez l'utilisateur vers la page des recettes ou une autre page appropriée
        return $this->redirectToRoute('app_home');
    }


}