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
                    "class" => "bg-light"
                ]
            ])
            ->add('ref', TextType::class, [
                "label" => "Référence du produit",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "FOCOMOTCAAMM",
                    "class" => "bg-light"
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Optique' => "Optique",
                    'Solaire' => "Solaire",
                    'Capsule' => "Capsule",
                ],
                "label" => "Catégorie",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "vue ou solaire",
                    "class" => "bg-light "
                ]
            ])
            ->add('forme', TextType::class, [
                "label" => "Forme",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "nom de la forme",
                    "class" => "bg-light "
                ]
            ])
            ->add('couleur', TextType::class, [
                "label" => "Couleur",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "nom de la couleur",
                    "class" => "bg-light "
                ]
            ])
            ->add('taille', TextType::class, [
                "label" => "Taille",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "taille",
                    "class" => "bg-light "
                ]
            ])
            ->add('motif', TextType::class, [
                "label" => "Motif ",
                "required" => false,
                "empty_data" => '',
                 "attr" => [
                    "placeholder" => "motif",
                    "class" => "bg-light"
                ]
            ])
            ->add('prix_pub_ttc', MoneyType::class,[
                "label"=> "Prix public conseillé TTC",
                "required" => false,
                "attr" => [
                    "class" => "bg-light"
                ]
            ])
            ->add('prix_rev_ht', MoneyType::class, [
                "label"=> "Prix revendeur HT",
                "required" => false,
                "attr" => [
                    "class" => "bg-light"
                ]
            ])
            ->add('description', TextareaType::class, [
                "label" => "Description ",
                "required" => false,
                 "attr" => [
                    "placeholder" => "description",
                    "class" => "bg-light"
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
