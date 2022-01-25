<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('oldPassword', TextType::class, [
            "required" => false,
            "label" => "Ancien mot de passe"
        ])

        ->add('newPassword', TextType::class, [
            "required" => false,
            "label" => "Nouveau mot de passe"
        ])
        ->add('confirmPassword', TextType::class, [
            "required" => false,
            "label" => "Confirmation du nouveau mot de passe"
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
