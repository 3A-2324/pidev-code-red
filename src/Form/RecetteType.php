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

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Categorie')
            ->add('image', FileType::class, [
                'label' => 'Votre Image (JPG, JPEG, PNG file)',
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*'],
            ])
            ->add('description')
            ->add('calorie_recette')
            ->add('Ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'multiple' => true,
                'expanded' => true, 
                'choice_label' => 'Nom', 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
