<?php

namespace App\Controller;

use App\Form\ProfileEditType;
use App\Form\ChangePasswordType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');

        }
        $categories = $categoryRepository->findAll();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');

        }

        $categories = $categoryRepository->findAll();

        // Création du formulaire de mise à jour du profil
        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('profileImage')->getData();
    
            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
    
                $uploadDirectory = $this->getParameter('upload_directory') . '/profil/users';
                try {
                    $uploadedFile->move($uploadDirectory, $newFilename);
                } catch (FileException $e) {
                    // Gérer l'erreur en conséquence
                }

                $user->setProfileImage($newFilename);
            }
    
            $entityManager->flush();
    
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
    
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'categories' => $categories,
        ]);
    }


     #[Route('/change_password', name: 'app_change_password')]
     public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
     {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');

        }
 
         $form = $this->createForm(ChangePasswordType::class);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $newPassword = $form['password']['first']->getData();
 
 
             // Hachez et mettez à jour le nouveau mot de passe de l'utilisateur
             $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
             $user->setPassword($hashedPassword);
 
             // Enregistrez les modifications dans la base de données
             $entityManager->persist($user);
             $entityManager->flush();
 
             $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');
             return $this->redirectToRoute('app_profile'); // Remplacez "app_home" par la route vers votre page d'accueil
         }
 
         return $this->render('profile/change_password.html.twig', [
             'form' => $form->createView(),
         ]);
     }

}
