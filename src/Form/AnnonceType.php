<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Equide;
use App\Entity\Typeannonce;
use App\Entity\Utilisateur;
use App\Form\EquideType;
use PHPUnit\Util\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
           ->add('description')
            ->add('prix')
         ->add('idtypea')
            ->add('equide', EquideType::class, [
                'mapped' => false
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
