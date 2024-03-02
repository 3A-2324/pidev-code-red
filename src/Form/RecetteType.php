<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Categorie', ChoiceType::class, [
                'choices' => [
                    'Dejeuner et Brunch' => 'Dejeuner_et_Brunch',
                    'Dessert' => 'Dessert',
                    'Plats Principaux' => 'Plats_Principaux',
                    'Collation' => 'Collation',
                ],
                'placeholder' => 'Choose a category', // Optional placeholder
                'label' => 'Categorie', // Optional label
                'attr' => ['class' => 'form-control'], // Optional CSS class
            ])
            ->add('image', FileType::class, [
                'label' => 'Votre Image (JPG, JPEG, PNG file)',
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*'],
            ])
            ->add('description',TextareaType::class ,['attr'=>['row'=>20 ,'cols'=>60]])
            ->add('calorie_recette')
            ->add('Ingredients');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
