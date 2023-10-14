<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Doctrine\DBAL\Types\TextType;
use Symfony\{Component\Form\AbstractType,
    Component\Form\Extension\Core\Type\CheckboxType,
    Component\Form\Extension\Core\Type\PasswordType,
    Component\Form\Extension\Core\Type\SubmitType,
    Component\Form\Extension\Core\Type\TextareaType,
    Component\Form\FormBuilderInterface,
    Component\OptionsResolver\OptionsResolver,
    Component\Validator\Constraints\IsTrue,
    Component\Validator\Constraints\Length,
    Component\Validator\Constraints\NotBlank};

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'label' => "Prénom",
                    'placeholder' => 'Veuillez saisir votre prénom']
            ])
            ->add('nom',\Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'label' => "Nom",
                    'placeholder' => 'Veuillez saisir votre prénom']
            ])
            ->add('login',\Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'label' => "Pseudo",
                    'placeholder' => 'Veuillez saisir votre pseudo']
            ])
            ->add('mail',\Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'label' => "Mail",
                    'placeholder' => 'Veuillez saisir votre mail']
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                    'class' => 'input is-warning',
                    'label' => 'Mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => "Valider l'inscription",
                'attr' => ['class' => 'button is-warning']
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
