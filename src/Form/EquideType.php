<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Equide;
use App\Entity\Race;
use App\Entity\Robe;
use App\Entity\Typeequide;
use App\Repository\TypeEquideRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class EquideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de l'équidé",
                'attr' => [
                    'class' => 'input',
                    'placeholder' => "Nom de l'équidé"
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le nom est obligatoire"
                    ])
                ]
            ])
            ->add('datenaiss', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'input',
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "La date de naissance est obligatoire"
                    ])
                ]
            ])
            ->add('robe', EntityType::class, [
                'class' => Robe::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Sélectionner une robe',
                'attr' => [
                    'class' => 'select'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "La robe est obligatoire"
                    ])
                ]
            ])
            ->add('race', EntityType::class, [
                'class' => Race::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Sélectionner une race',
                'attr' => [
                    'class' => 'select'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "La race est obligatoire"
                    ])
                ]
            ])
            ->add('taille', NumberType::class, [
                'label' => 'Taille (cm)',
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'Taille en centimètres',
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "La taille est obligatoire"
                    ]),
                    new Range([
                        'min' => 60,
                        'max' => 195,
                        'notInRangeMessage' => 'La taille doit être comprise entre {{ min }}cm et {{ max }}cm'
                    ])
                ]
            ])
            ->add('lienhn', UrlType::class, [
                'label' => 'Lien Haras Nationaux',
                'required' => false,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'https://...'
                ],
                'label_attr' => [
                    'class' => 'label'
                ]
            ])
            ->add('typeeq', EntityType::class, [
                'class' => TypeEquide::class,
                'choice_label' => 'libelle',
                'placeholder' => "Sélectionner un type d'équidé",
                'label' => "Type d'équidé",
                'attr' => [
                    'class' => 'select'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'query_builder' => function(TypeEquideRepository $typeEquideRepository) {
                    return $typeEquideRepository->createQueryBuilder("type_equide")
                        ->orderBy('type_equide.libelle');
                },
                'constraints' => [
                    new NotBlank([
                        'message' => "Le type d'équidé est obligatoire"
                    ])
                ]
            ])
            ->add('dep', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Sélectionner un département',
                'label' => "Localisation ",
                'attr' => [
                    'class' => 'select'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le département est obligatoire"
                    ])
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equide::class,
        ]);
    }
}
