<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\SuiviObjectif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuiviObjectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_suivi')
        ->add('nouveau_poids')
        ->add('commentaire')
        ->add('id_objectif', EntityType::class, [
            'class' => 'App\Entity\Objectif',
            'choice_label' => 'id', // Laissez 'id' si c'est le champ que vous voulez utiliser comme libellé
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuiviObjectif::class,
        ]);
    }
}
