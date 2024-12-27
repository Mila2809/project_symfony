<?php

namespace App\Form;

use App\Entity\Commandes;
use App\Entity\ContenuPanier;
use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateAchat', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data'  => null,
            ])
            ->add('Utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'id',
            ])
            ->add('ContenuPaniers', EntityType::class, [
                'class' => ContenuPanier::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'CEST LA MERDE'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commandes::class,
        ]);
    }
}
