<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Equide;
use App\Entity\Typeannonce;
use App\Entity\Utilisateur;
use App\Form\EquideType;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PHPUnit\Util\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => [
        'class' => 'input is-warning',
                'label' => "Titre de l'annonce",
                    'placeholder' => 'Veuillez saisir un titre']])
           ->add('description',TextType::class, [
               'attr' => [
                   'class' => 'input is-warning',
                   'label' => "Description",
                   'placeholder' => 'Veuillez saisir une description']])
            ->add('prix',IntegerType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'label' => "Prix"]])
         ->add('idtypea',ChoiceType::class, [
             'choices' => [
                 'vente' => 1,
                 'location' => 2,
                 'demi-pension' => 3
             ],
             'attr' => [
                 'class' => 'input is-warning',
                 'label' => "Type de l'annonce",
                 ]])
            ->add('equide', EquideType::class, [
                'mapped' => false,
                'attr' => ['class' => 'input is-warning']
            ])
            ->add('save', SubmitType::class, [
                'label' => "Publier l'annonce",
                'attr' => ['class' => 'button is-warning']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
