<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Equide;
use App\Entity\Typeannonce;
use App\Entity\Typeequide;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class EquideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('datenaiss',DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text'])
            ->add('robe')
            ->add('race')
            ->add('taille')
            ->add('lienhn')
            ->add('idtypeeq',TypeEquideType::class, [
        'data_class' => Typeequide::class,
    ])
            ->add('iddep', DepartementType::class,[
                'data_class' =>Departement::class,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equide::class,
        ]);
    }
}
