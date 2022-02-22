<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('adresse', TextType::class, [
                "label" => "Adresse",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "class" => "bg-light"
                ]
            ])

            ->add('cp', TextType::class, [
                "label" => "Code postal",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "class" => "bg-light"
                ]
            ])
            ->add('ville', TextType::class, [
                "label" => "Ville",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "class" => "bg-light"
                ]
            ])
            ->add('pays',TextType::class, [
                "label" => "Pays",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "class" => "bg-light",
                    "value" => "France"
                ]
            ])
            ->add('facturation')
            ->add('livraison')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
