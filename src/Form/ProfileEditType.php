<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use App\Transformer\FileToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class ProfileEditType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
        ->add('email', EmailType::class, [
            'label' => 'Votre adresse mail',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('LastName', TextType::class, [
            'label' => 'Votre nom',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('FirstName', TextType::class, [
            'label' => 'Votre prénom',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('profileImage', FileType::class, [
            'label' => 'Image de profil',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG ou PNG.',
                ]),
            ],
            'attr' => [
                'class' => 'form-control-file my-3',
            ],
        ]);
    

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}