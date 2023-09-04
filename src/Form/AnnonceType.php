<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Equide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
          //  ->add('description')
            //->add('prix')
         //   ->add('activation')
           // ->add('datecreation')
         //->add('equide', Equide::class, array(
            // 'label' => 'nom',
            // 'mapped' => false
           //  'choices' => array(
           //      'VIP' => 'VIP',
           //      'Empresa' => 'Empresa'
          //   )
         //))
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
