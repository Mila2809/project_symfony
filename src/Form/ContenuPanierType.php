<?php

namespace App\Form;

use App\Entity\Commandes;
use App\Entity\ContenuPanier;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContenuPanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Quantite')
            ->add('Produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'id',
                'disabled' => true,
            ])
            ->add('commandes', EntityType::class, [
                'class' => Commandes::class,
                'choice_label' => 'id',
                'disabled' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'JSP CE QUE JE FAIS'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContenuPanier::class,
        ]);
    }
}
