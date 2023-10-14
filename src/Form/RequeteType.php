<?php

namespace App\Form;

use App\Entity\Requete;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequeteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet',TextType::class, [
        'attr' => [
            'class' => 'input is-warning',
            'label' => "Objet",
            'placeholder' => "Veuillez saisir l'objet de la demande"]])
            ->add('description',TextareaType::class, [
        'attr' => [
            'class' => 'input is-warning',
            'label' => "Description",
            'placeholder' => "Veuillez décrire votre demande"]])
            ->add('save', SubmitType::class, [
                'label' => "Valider la demande",
                'attr' => ['class' => 'button is-warning']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Requete::class,
        ]);
    }
}
