<?php

namespace App\Form;

use App\Entity\Basket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BasketSubjectCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class) // Utilisation de TextType pour le champ name
            ->add('description', TextType::class) // Utilisation de TextType pour le champ description
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Basket::class,
        ]);
    }
}
