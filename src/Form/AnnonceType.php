<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Equide;
use App\Entity\Typeannonce;
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
         //   ->add('activation')
           // ->add('datecreation')
         //->add('typeannonce.libelle', Typeannonce::class)
            //->add('idutilisateurannonce')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
