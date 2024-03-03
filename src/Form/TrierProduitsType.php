<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TrierProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tri', ChoiceType::class, [
                'choices' => [
                    'Nom du produit (A-Z)' => 'asc',
                    'Nom du produit (Z-A)' => 'desc',
                ],
                'label' => 'Trier par :',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
