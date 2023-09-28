<?php

namespace App\Form;

use App\Entity\Requete;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequeteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet')
            ->add('description')
         // ->add('idauteurrequete', UtilisateurType::class,[
           //   'data_class' => Utilisateur::class,
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Requete::class,
        ]);
    }
}
