<?php

namespace App\Form;

use App\Entity\Equide;
use App\Entity\Typeequide;
use App\Repository\TypeEquideRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EquideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', options:[
                'label' => "Nom de l'équidé : "
            ])
            ->add('datenaiss',DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text'])
            ->add('robe')
            ->add('race')
            ->add('taille')
            ->add('lienhn')
            ->add('typeeq', EntityType::class, [
                'placeholder' => "Veuillez saisir un type de l'annonce",
                'label' => "Type de l'annonce : ",
                'class' => TypeEquide::class,
                'choice_label' => 'libelle',
                'query_builder' => function(TypeEquideRepository $typeEquideRepository) {
                    return $typeEquideRepository->createQueryBuilder("type_equide")->addOrderBy('type_equide.libelle');
                },
                'attr' => [
                    'class' => 'input is-primary',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Choisir un type d'annonce ",
                    ])
                ],
            ])
            ->add('dep');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equide::class,
        ]);
    }
}
