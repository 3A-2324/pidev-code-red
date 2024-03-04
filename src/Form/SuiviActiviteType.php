<?php

namespace App\Form;

use App\Entity\SuiviActivite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuiviActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class, array('required' => true, 'attr' => array('class' => 'form-control')))
            ->add('rep',TextType::class, array('required' => true, 'attr' => array('class' => 'form-control')))
            ->add('activite', EntityType::class, [
                'class' => 'App\Entity\Activite',
                'choice_label' => 'nom','required' => true, 'attr' => array('class' => 'form-control')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuiviActivite::class,
        ]);
    }
}
