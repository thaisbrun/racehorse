<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom',TextType::class, [
                'label' => "Prénom : ",
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'Veuillez saisir votre prénom'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom ne peut pas être vide',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le prénom doit au moins faire 2 caractères',
                        'max' => 20,
                        'maxMessage' => 'Le prénom doit au maximum faire 20 caractères',
                    ]),
                ]
            ])
            ->add('nom',TextType::class, [
                'label' => "Nom : ",
        'attr' => [
            'class' => 'input',
            'placeholder' => 'Veuillez saisir votre nom'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne peut pas être vide',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit au moins faire 3 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                        'maxMessage' => 'Le nom doit au maximum faire 20 caractères',
                    ]),
                ]
            ])
            ->add('login',TextType::class, [
                'label' => "Pseudo : ",
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'Veuillez saisir votre pseudo'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le pseudo ne peut pas être vide',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le pseudo doit au moins faire 3 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                        'maxMessage' => 'Le pseudo doit au maximum faire 20 caractères',
                    ]),
                ],
            ])
            ->add('mail',TextType::class, [
                'label' => "Mail : ",
        'attr' => [
            'class' => 'input',
            'placeholder' => 'Veuillez saisir votre mail'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mail ne peut pas être vide',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
