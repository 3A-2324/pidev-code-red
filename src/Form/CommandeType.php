<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_cmd')
            ->add('etat_cmd', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'En cours',
                    'En attente' => 'En attente',
                    'Validé' => 'Validé',
                ],
            ])
            ->add('qte_commande')
            ->add('total')

            ->add('produits')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
