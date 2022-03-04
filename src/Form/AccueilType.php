<?php

namespace App\Form;

use App\Entity\Accueil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AccueilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                "label" => "titre ",
                "required" => false,
                "attr" => [
                    "class" => "bg-light input-check"
                ]
            ])
            
            ->add('video', CheckboxType::class, [
                "label" => "video ",
                "required" => false,
                
                
                    
            ])

            ->add('active', CheckboxType::class, [
                "label" => "active ",
                "required" => false,
            ])

            ->add('large', CheckboxType::class, [
                "label" => "photo/video large ",
                "required" => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accueil::class,
        ]);
    }
}
