<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom',TextType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'label' => "Prénom",
                    'placeholder' => 'Veuillez saisir votre prénom']])
            ->add('nom',TextType::class, [
        'attr' => [
            'class' => 'input is-warning',
            'label' => "Nom",
            'placeholder' => 'Veuillez saisir votre prénom']])
            ->add('login',TextType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'label' => "Pseudo",
                    'placeholder' => 'Veuillez saisir votre pseudo']])
            ->add('mail',TextType::class, [
        'attr' => [
            'class' => 'input is-warning',
            'label' => "Mail",
            'placeholder' => 'Veuillez saisir votre mail']])
            ->add('password',PasswordType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'label' => "Mot de passe",
                    'placeholder' => 'Veuillez saisir votre mot de passe']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
