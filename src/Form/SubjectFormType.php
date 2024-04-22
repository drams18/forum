<?php

namespace App\Form;

use App\Entity\Subject;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('category', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'name',
                'label' => 'Thème',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}


        // $builder
        //     ->add('name')
        //     ->add('description')
        //     ->add('category', EntityType::class, [
        //         'class' => Theme::class,
        //         'choice_label' => 'id',
        //     ])
        //     ->add('owner', EntityType::class, [
        //         'class' => User::class,
        //         'choice_label' => 'id',
        //     ])
        //     ->add('theme', EntityType::class, [
        //         'class' => Theme::class,
        //         'choice_label' => 'id',
        //     ])
        // ;