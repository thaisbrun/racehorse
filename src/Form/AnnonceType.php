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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => "Titre de l'annonce : ",
                'attr' => [
        'class' => 'input is-warning',
                    'placeholder' => 'Veuillez saisir un titre']])
           ->add('description',TextType::class, [
               'attr' => [
                   'class' => 'input is-warning',
                   'label' => "Description",
                   'placeholder' => 'Veuillez saisir une description'],
               'constraints' => [
                   new NotBlank([
                       'message' => 'Entrez un titre',
                   ]),
                   new Length([
                       'min' => 10,
                       'minMessage' => "Le titre doit faire au minimum 10 caractères",
                       // max length allowed by Symfony for security reasons
                       'max' => 400,
                   ]),
               ],])
            ->add('prix',IntegerType::class, [
                'attr' => [
                    'class' => 'input is-warning',
                    'min' => 1,
                    'max' => 100000,
                    'placeholder' => "Veuillez saisir un prix",
                    'label' => "Prix"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un prix',
                    ])]
            ])
         ->add('idtypea',ChoiceType::class, [
             'placeholder' => "Veuillez saisir un type de l'annonce",
             'label' => "Type de l'annonce",
             'choices' => [
                 'vente' => 1,
                 'location' => 2,
                 'demi-pension' => 3
             ],
             'attr' => [
                 'class' => 'input is-warning',
                 ],
             'constraints' => [
                 new NotBlank([
                     'message' => "Choisir un type d'annonce ",
                 ])
             ],
             ])
            ->add('equide', EquideType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez remplir les informations nécessaires ",
                    ])
                ]
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
            'attr' => ['class' => 'is outlined is-warning']
        ]);
    }
}
