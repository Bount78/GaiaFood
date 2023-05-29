<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\ListedeCourses;
use App\Repository\RecetteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ListCoursesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
        ->add('Liste', EntityType::class, [
            'label' => 'Sélectionnez les recettes',
            'class' => Recette::class,
            'choice_label' => 'title',
            'multiple' => true,
            'expanded' => true,

        ])
        // ->add('user')
        ->add('submit', SubmitType::class, [
            'label' => 'Générer PDF',
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListedeCourses::class,
        ]);
    }
}