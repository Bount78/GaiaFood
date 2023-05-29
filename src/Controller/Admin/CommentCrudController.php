<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\User;
use App\Entity\Comment;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('fk_user')
            ->setLabel('Commenté par')
            ->formatValue(function ($value, $entity) {
                if ($entity->getFkUser() instanceof User) {
                    $fullName = $entity->getFkUser()->getFullName();
                    if (!empty($fullName)) {
                        return $fullName;
                    } else {
                        return $entity->getFkUser()->getEmail();
                    }
                }
                return '';
            })
            ->onlyOnIndex()
            ->autocomplete(),
            TextareaField::new('text')
            ->setLabel('Contenu'),

            DateTimeField::new('CreatedAt')
            ->setLabel('Posté le'),

            DateTimeField::new('UpdatedAt')
            ->setLabel('Modifié le'),

        ];
    }

}
