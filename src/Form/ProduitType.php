<?php

namespace App\Form;

use App\Entity\Commandes;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'attr' => [
                    'class' => 'my-class'
                ]
            ])
            // ->add('Nom')
            ->add('Description')
            ->add('Prix')
            ->add('Stock')
            ->add('Photo', FileType::class, [
                'label' => 'Photo (jpg, jpeg, png)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'Photo/jpg',
                            'Photo/jpeg',
                            'Photo/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Photo (jpg, jpeg, png)',
                    ]),
            ]])

            ->add('save', SubmitType::class, ['label' => 'Create produit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
