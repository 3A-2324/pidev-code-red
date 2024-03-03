<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom')
        ->add('categorieing', ChoiceType::class, [
            'choices' => [
                'cereales' => 'cereales',
                'produits laitiers' => 'produits_laitiers',
                'fruits' => 'fruits',
                'legumes' => 'legumes',
                'matieres grasses' => 'matieres_grasses',
                'viandes' => 'viandes',
                'sucreries' => 'sucreries',

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
        ]);

        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
            'ingredient_names' => [], // Définit par défaut comme un tableau vide
        ]);
    }
}
