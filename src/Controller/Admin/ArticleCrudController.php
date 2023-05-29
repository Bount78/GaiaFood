<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

 
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
            ->setLabel('Titre'),
            
            TextEditorField::new('content')
            ->setLabel('Contenu'),

            DateTimeField::new('createdAt')
            ->setLabel('Crée le'),

            DateTimeField::new('updatedAt')
            ->setLabel('Modifié le'),

            AssociationField::new('user')
            ->setLabel('Crée par')
            ->formatValue(function ($value, $entity) {
                if ($entity->getUser() instanceof User) {
                    $fullName = $entity->getUser()->getFullName();
                    if (!empty($fullName)) {
                        return $fullName;
                    } else {
                        return $entity->getUser()->getEmail();
                    }
                }
                return '';
            })
            ->onlyOnIndex()
            ->autocomplete()  
        ];
    }
  
}
