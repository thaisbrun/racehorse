<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Typeannonce;
use App\Repository\TypeAnnonceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
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
                    'class' => 'input',
                    'placeholder' => 'Veuillez saisir un titre']])
           ->add('description',TextareaType::class, [
               'label' => "Description de l'annonce : ",
               'attr' => [
                   'class' => 'input',
                       'style' => 'max-width: 50%',
                   'placeholder' => 'Décrivez votre animal en quelques phrases'],
               'constraints' => [
                   new NotBlank([
                       'message' => 'Entrez une description',
                   ]),
                   new Length([
                       'min' => 10,
                       'minMessage' => "La description doit faire au minimum 10 caractères",
                       // max length allowed by Symfony for security reasons
                       'max' => 400,
                   ]),
               ],])
            ->add('prix',IntegerType::class, [
                'label' => "Prix : ",
                'attr' => [
                    'class' => 'input',
                    'min' => 1,
                    'max' => 10000000,
                    'style' => 'max-width: 50%',
                    'placeholder' => "Veuillez saisir un prix (en euros)"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un prix',
                    ])]
            ])
         ->add('typea',EntityType::class, [
             'placeholder' => "Veuillez saisir un type de l'annonce",
             'label' => "Type de l'annonce : ",
             'class' => Typeannonce::class,
             'choice_label' => 'libelle',
             'query_builder' => function(TypeAnnonceRepository $typeAnnonceRepository) {
                 return $typeAnnonceRepository->createQueryBuilder("type_annonce")->addOrderBy('type_annonce.libelle');
             },
             'attr' => [
                 'class' => 'input',
                 ],
             'constraints' => [
                 new NotBlank([
                     'message' => "Choisir un type d'annonce ",
                 ])
             ],
             ])
            ->add('equide', EquideType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez remplir les informations nécessaires ",
                    ])
                ]
            ])
            ->add('images', FileType::class, [
                'multiple' => true, // Permet à l'utilisateur de sélectionner plusieurs images
                'mapped' => false, // Ces champs ne correspondent pas directement aux propriétés de l'entité Annonce
                'required' => false, // Ne pas exiger que les images soient fournies
            ]);
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
