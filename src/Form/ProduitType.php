<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "label" => "Nom du produit",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "saisir le nom du produit",
                    "class" => "bg-light input-form"
                ]
            ])
            ->add('ref', TextType::class, [
                "label" => "Référence du produit",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "ref",
                    "class" => "bg-light input-form"
                ]
            ])
            ->add('categorie', ChoiceType::class, [           
                'choices' => [
                    'Optique' => "Optique",
                    'Solaire' => "Solaire",
                    'Capsule' => "Capsule",
                    'Accessoire' => "Accessoire",
                ],
                "label" => "Catégorie",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "vue ou solaire",
                    "class" => "bg-light input-form"
                ]
            ])
            ->add('matiere', TextType::class, [
                "label" => "Matières",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "nom des matières",
                    "class" => "bg-light input-form "
                ]
            ])
            ->add('couleur', ChoiceType::class, [           
                'choices' => [
                    'Ecaille' => "Ecaille",
                    'Noir' => "Noir",
                    'Cristal' => "Cristal",
                ],
                "label" => "couleur",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "vue ou solaire",
                    "class" => "bg-light input-form"
                ]
            ])

            ->add('taille', TextType::class, [
                "label" => "Taille",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "taille",
                    "class" => "bg-light input-form"
                ]
            ])
            ->add('motif', TextType::class, [
                "label" => "Motif ",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "motif",
                    "class" => "bg-light input-form"
                ]
            ])
            ->add('prix_pub_ttc', MoneyType::class,[
                "label"=> "Prix public conseillé TTC",
                "required" => false,
                "attr" => [
                    "class" => "bg-light input-form"
                ]
            ])
            ->add('prix_rev_ht', MoneyType::class, [
                "label"=> "Prix revendeur HT",
                "required" => false,
                "attr" => [
                    "class" => "bg-light input-form"
                ]
            ])
            ->add('description', TextareaType::class, [
                "label" => "Description ",
                "required" => false,
                 "attr" => [
                    "placeholder" => "description",
                    "class" => "bg-light text-area"
                ]
            ])
            ->add('entretien', TextareaType::class, [
                "label" => "Entretien ",
                "required" => false,
                 "attr" => [
                    "placeholder" => "entretien",
                    "class" => "bg-light text-area"
                ]
            ])

            ->add('showcase', CheckboxType::class, [
                "label" => "showcase ",
                "required" => false,
                 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
