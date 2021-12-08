<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "label" => "nom du produit",
                "required" => false,
                 "attr" => [
                    "placeholder" => "saisir le nom du produit",
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('ref', TextType::class, [
                "label" => "Référence du produit",
                "required" => false,
                 "attr" => [
                    "placeholder" => "FOCOMOTCAAMM",
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('categorie', TextType::class, [
                "label" => "Catégorie",
                "required" => false,
                 "attr" => [
                    "placeholder" => "vue ou solaire",
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('forme', TextType::class, [
                "label" => "Forme",
                "required" => false,
                 "attr" => [
                    "placeholder" => "nom de la forme",
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('couleur', TextType::class, [
                "label" => "Couleur",
                "required" => false,
                 "attr" => [
                    "placeholder" => "nom de la couleur",
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('taille', TextType::class, [
                "label" => "Taille",
                "required" => false,
                 "attr" => [
                    "placeholder" => "taille",
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('motif', TextType::class, [
                "label" => "Motif ",
                "required" => false,
                 "attr" => [
                    "placeholder" => "motif",
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('prix_pub_ttc', MoneyType::class,[
                "label"=> "Prix public conseillé TTC",
                "required" => false,
                "attr" => [
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('prix_rev_ht', MoneyType::class, [
                "label"=> "Prix revendeur HT",
                "required" => false,
                "attr" => [
                    "class" => "bg-light text-white"
                ]
            ])
            ->add('photo', FileType::class, [
                "multiple"=> true,
                "required" => false,
                "attr" => [
                    "class" => "bg-light text-dark"
                ]
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
