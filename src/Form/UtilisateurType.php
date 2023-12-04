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
                'label' => "Prénom",
                'attr' => [
                    'class' => 'input is-warning',
                    'placeholder' => 'Veuillez saisir votre prénom']])
            ->add('nom',TextType::class, [
                'label' => "Nom",
        'attr' => [
            'class' => 'input is-warning',
            'placeholder' => 'Veuillez saisir votre prénom']])
            ->add('login',TextType::class, [
                'label' => "Pseudo",
                'attr' => [
                    'class' => 'input is-warning',
                    'placeholder' => 'Veuillez saisir votre pseudo']])
            ->add('mail',TextType::class, [
                'label' => "Mail",
        'attr' => [
            'class' => 'input is-warning',
            'placeholder' => 'Veuillez saisir votre mail']])
            ->add('password',PasswordType::class, [
                'label' => "Mot de passe",
                'attr' => [
                    'class' => 'input is-warning',
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
