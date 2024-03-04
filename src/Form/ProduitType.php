<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_produit')
            ->add('Description')
            ->add('Prix')
            ->add('image', FileType::class, [
                'label' => 'Your Image (JPG, JPEG, PNG file)',
                'mapped' => false, // tells Symfony not to try to map this field to any entity property
                'required' => false, // allow the field to be empty, so you can remove the image
                'attr' => ['accept' => 'image/*'],
            ])
            // ->add('commandes')
            // ->add('panier');
            ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
