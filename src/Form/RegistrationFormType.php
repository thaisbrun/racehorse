<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\{Component\Form\AbstractType,
    Component\Form\Extension\Core\Type\EmailType,
    Component\Form\Extension\Core\Type\PasswordType,
    Component\Form\Extension\Core\Type\SubmitType,
    Component\Form\FormBuilderInterface,
    Component\OptionsResolver\OptionsResolver,
    Component\Validator\Constraints\Length,
    Component\Validator\Constraints\NotBlank,
    Component\Validator\Constraints\Regex};

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
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
            ->add('nom',\Symfony\Component\Form\Extension\Core\Type\TextType::class, [
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
            ->add('login',\Symfony\Component\Form\Extension\Core\Type\TextType::class, [
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
            ->add('mail',EmailType::class, [
                'label' => "Mail : ",
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'Veuillez saisir votre mail'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mail ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe : ',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                    'class' => 'input',
                    'placeholder' => 'Veuillez saisir votre mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut pas être vide',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit au moins faire 8 caractères',
                        'max' => 15,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/',
                        'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => "Valider l'inscription",
                'attr' => ['class' => 'button buttonLight is-small ml-4 mt-1']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
