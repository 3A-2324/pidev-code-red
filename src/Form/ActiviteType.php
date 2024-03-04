<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('type',TextType::class, array('required' => true, 'attr' => array('class' => 'form-control')))
            ->add('nom',TextType::class, array('required' => true, 'attr' => array('class' => 'form-control')))
            ->add('nbrCal',TextType::class, array('required' => true, 'attr' => array('class' => 'form-control')))
            ->add('image', FileType::class, array('required' => true, 'attr' => array('class' => 'form-control')))
            ->add('description',TextType::class, array('required' => true, 'attr' => array('class' => 'form-control')))
            ->add('video', FileType::class, array('required' => true, 'attr' => array('class' => 'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}