<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Equide;
use App\Entity\Typeannonce;
use PHPUnit\Util\Type;
use Symfony\Component\Form\AbstractType;
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
         ->add('idtypea',TypeAnnonceType::class, [
             'data_class' => Typeannonce::class,
         ])
           // ->add('idutilisateurannonce')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
