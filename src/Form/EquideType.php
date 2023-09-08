<?php

namespace App\Form;

use App\Entity\Equide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('datenaiss')
            ->add('robe')
            ->add('race')
            ->add('taille')
            ->add('lienhn')
            ->add('idtypeeq')
            ->add('iddep')
            //->add('idproprio')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equide::class,
        ]);
    }
}
