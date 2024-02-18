<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class,  ['attr' => [
                'placeholder' => ' Nom' // Texte à afficher en arrière-plan
            ]
        ])
            ->add('Prenom', TextType::class,  ['attr' => [
                'placeholder' => 'Prénom' // Texte à afficher en arrière-plan
            ]
        ])
            ->add('Date_de_naissance', DateType::class, [
                
                'attr' => [
                    'placeholder' => 'YYYY-MM-DD' // Texte à afficher en arrière-plan
                ]
            ])
            ->add('Genre', ChoiceType::class, [
                'choices' => [
                    'Genre' => '',
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                    
                ],
                'required' => true,
            ])
            ->add('Adresse', TextType::class,  ['attr' => [
                'placeholder' => ' Adresse' // Texte à afficher en arrière-plan
            ]
        ])
            ->add('Num_de_telephone', TextType::class,  ['attr' => [
                'placeholder' => ' Numéro de téléphone' // Texte à afficher en arrière-plan
            ]
        ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Role' => '',
                    'Client' => 'Client',
                    'Administrateur' => 'Administreateur',
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'placeholder' => ' adresse' // Texte à afficher en arrière-plan
                ]
            ])
                
           
            ->add('Mot_de_passe', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
                'attr' => [
                    'placeholder' => ' Mot de passe ' // Texte à afficher en arrière-plan
                ]
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
