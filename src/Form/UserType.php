<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

            if ($options['inscription']) {

            
                $builder
                    ->add('email', TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                    ->add('password',RepeatedType::class,[
                        "required" =>false,
                        "type" => PasswordType::class,
                        "first_name" =>"first",
                        "second_name" =>"second",
                        "invalid_message" => "Les mots de passe ne sont pas identiques",
                        "first_options"=>[
                            "label" => "Mot de passe",
                            "required"=> false
                        ],
                        "second_options"=>[
                            "label" => "Confirmer le mot de passe",
                            "required"=> false
                        ],
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                    ->add('nom',TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                    ->add('prenom',TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                ;
            } elseif ($options['profil']) {

                $builder
                    ->add('email', TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                    ->add('nom',TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                    ->add('prenom',TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                ;
            } elseif ($options['userAdmin']){
                $builder
                    ->add('email', TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                    ->add('nom',TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])
                    ->add('prenom',TextType::class,[
                        "required" =>false,
                        "attr" => [
                            "class" => "bg-light"
                        ]
                    ])

                    ->add('roles', ChoiceType::class, [
                            "required" =>false,
                            'choices' => ['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_USER' => 'ROLE_USER' , 'ROLE_REVENDEUR' => 'ROLE_REVENDEUR'],
                            'expanded' => true,
                            'multiple' => true,
                        ]
                    )
                ;

            }
            

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'userAdmin' => false, 
            'inscription' => false, 
            'profil' => false // on met ?? false les options cr????es pour diff??rencier les builders car en cas de modicication on ne veut pas toucher au mot de passe
        ]);
    }
}
