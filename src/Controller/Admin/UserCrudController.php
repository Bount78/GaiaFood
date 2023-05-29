<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->setLabel('Identifiant'),

            EmailField::new('email')
            ->setLabel('Adresse mail'),

            ArrayField::new('roles')
            ->setLabel('Statut'),

            TextField::new('LastName')
            ->setLabel('Nom de famille'),

            TextField::new('FirstName')
            ->setLabel('Prénom'),
            
            AssociationField::new('articles')
            ->setLabel('Nombre d\'articles')
            ->formatValue(function ($value, $entity) {
                $articleCount = $entity->getArticles()->count();
                if ($articleCount > 0) {
                    return $articleCount;
                } else {
                    return 'N/A';
                }
            })
            ->onlyOnIndex()
        
        ];
    }

}
