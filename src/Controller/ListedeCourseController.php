<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Recette;
use App\Form\ListCoursesType;
use App\Entity\ListedeCourses;
use App\Repository\RecetteRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListedeCourseController extends AbstractController
{

    private $recetteRepository;

    public function __construct(RecetteRepository $recetteRepository)
    {
        $this->recetteRepository = $recetteRepository;
    }


    #[Route('/listede/course', name: 'app_listede_course')]
    public function index(CategoryRepository $categoryRepository): Response
    {

        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }

        $categories = $categoryRepository->findAll();

        $listeDeCourses = $current_user->getListeCourses();



        return $this->render('listede_course/index.html.twig', [
            'name' => $current_user->getFirstName(),
            'liste_de_courses' => $listeDeCourses,
            'categories' => $categories,
        ]);
    }

    #[Route('/listede/course/creer', name: 'app_creer_course')]
    public function createList(Request $request, CategoryRepository $categoryRepository): Response
    {

        // Vérifier si l'utilisateur est identifié
        $current_user = $this->getUser();
        if (!$current_user) {
            return $this->redirectToRoute('app_logout');
        }


        $ListedeCourses = new ListedeCourses();
        $form = $this->createForm(ListCoursesType::class, $ListedeCourses);
        $form->handleRequest($request);
        $categories = $categoryRepository->findAll();
    
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $recetteIds = [];
            foreach ($formData->getListe() as $recette) {
                $recetteIds[] = $recette->getId();
            }
        
            if (empty($recetteIds)) {
                throw $this->createNotFoundException('Aucune recette sélectionnée.');
            }
        
            $recettes = $this->recetteRepository->findBy(['id' => $recetteIds]);
        
            if (empty($recettes)) {
                throw $this->createNotFoundException('Aucune recette trouvée.');
            }
        
            // Exemple : Utilisation de la bibliothèque PDF "Dompdf"
            $dompdf = new \Dompdf\Dompdf();

            // Styles CSS pour le graphisme du PDF
            $css = '
                h1 {
                    color: #346645;
                    font-size: 24px;
                    text-align: center;
                    font-family: "Arial", sans-serif;
                    margin-bottom: 20px;
                }
                h5 {
                    color: #D19C72;
                    font-size: 18px;
                    margin-bottom: 10px;
                    font-family: "Verdana", sans-serif;
                }
                ul {
                    list-style-type: disc;
                    columns: 2;
                    font-family: "Verdana", sans-serif;
                    margin-left: 20px;
                }
                .logo img {
                    max-width: 200px;
                }
                li {
                    color: #2D2E38;
                    font-size: 14px;
                    margin-bottom: 5px;
                }
            ';
            
            $html = '<style>' . $css . '</style>';
            $html .= '<div class="logo"><img src="{{ asset("build/images/logo.png") }}" alt="Logo"></div>';
            $html .= '<h1>Liste de courses</h1>';
            
            foreach ($recettes as $recette) {
                $ingredients = $recette->getIngredientText();
                $titleRecette = $recette->getTitle();
            
                $html .= '<h5>' . $titleRecette . '</h5>';
                $html .= '<p>' . $ingredients . '</p>';
            }
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $dompdf->stream('liste_courses.pdf', [
                'Attachment' => true,
            ]);
            
            return new Response();
            
        }
        

        return $this->render('listede_course/creer.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

}
