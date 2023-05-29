<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GaiaFood')
            ->setFaviconPath('<link rel="icon" href="build/images/favicon.ico">')
            ->renderContentMaximized()
            ;

    }

    public function configureMenuItems(): iterable
    {
        return [
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
        yield MenuItem::section('Création des utilisateurs'),
        yield MenuItem::linkToCrud('Article', 'fa-solid fa-newspaper', Article::class),
        yield MenuItem::linkToCrud('Commentaires', 'fa-solid fa-comment', Comment::class),
        yield MenuItem::section('Gestion des utilisateurs'),
        yield MenuItem::linkToCrud('Les utilisateurs', 'fa-solid fa-user-tie', User::class),
        yield MenuItem::section('Gestion des Catégories'),
        yield MenuItem::linkToCrud('Catégories', 'fa-solid fa-lines-leaning', Category::class),
        yield MenuItem::section('Autre'),
        yield MenuItem::linkToRoute('Espace utilisateur', 'fa-solid fa-house-laptop', 'app_home'),
        yield MenuItem::linkToLogout('Logout', 'fa-solid fa-right-from-bracket'),
        ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
    
    public function configureUserMenu(UserInterface $user): UserMenu
    {

        return parent::configureUserMenu($user)

            ->setName($user->getFullName())
            // use this method if you don't want to display the name of the user
            ->displayUserName(false)

            // you can return an URL with the avatar image
            // ->setAvatarUrl('https://...')
            // ->setAvatarUrl($user->getProfileImageUrl())
            // use this method if you don't want to display the user image
            ->displayUserAvatar(true)
            // you can also pass an email address to use gravatar's service
            // ->setGravatarEmail($user->getMainEmailAddress())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
                MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);
    }
}
