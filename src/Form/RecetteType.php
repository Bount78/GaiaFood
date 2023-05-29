<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Category;
use App\Entity\Ingredients;
use App\Form\IngredientsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de la recette',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la recette',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('preparationTime', IntegerType::class, [
                'label' => 'Temps de préparation',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('cookingTime', IntegerType::class, [
                'label' => 'Temps de cuisson',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('numberPortions', IntegerType::class, [
                'label' => 'Nombre de portions',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de la recette',
                'attr' => ['class' => 'form-control-file'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ])
                ],
            ])
            ->add('instruction', TextareaType::class, [
                'label' => 'Instructions',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ingredient_text', TextareaType::class, [
                'label' => 'Les ingrédients',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégories',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary btn-lg mt-3 col-12 text-light text-center'],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}