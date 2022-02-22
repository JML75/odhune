<?php

namespace App\Form;

use App\Entity\Presentation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PresentationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', TextType::class, [
            "label" => "Titre",
            "required" => false,
            "empty_data" => '',
             "attr" => [
                "placeholder" => "titre optionnel",
                "class" => "bg-light"
            ]
        ])
        ->add('sstitre', TextType::class, [
            "label" => "Sous-titre",
            "required" => false,
            "empty_data" => '',
             "attr" => [
                "placeholder" => "Sous-titre optionnel",
                "class" => "bg-light"
            ]
        ])
        ->add('presentation', TextareaType::class, [
            "label" => "Presentation ",
            "required" => false,
             "attr" => [
                "placeholder" => "Présentation",
                "class" => "bg-light",
                "rows" => "15"
            ]
        ])
        ->add('active', CheckboxType::class, [
            "label" => "active ",
            "required" => false,

             
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presentation::class,
        ]);
    }
}
