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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RequeteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet',TextType::class, [
                'label' => "Objet de votre demande :  ",
        'attr' => [
            'class' => 'input is-warning',
            'style' => 'width: 900px;',
            'placeholder' => "Veuillez saisir l'objet de la demande"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un objet',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => "L'objet de la demande doit faire au minimum 4 caractères",
                        'max' => 20,
                        'maxMessage' => "L'objet de la demande doit faire au maximum 20 caractères",
                        // max length allowed by Symfony for security reasons
                    ]),
                ]
                ])
            ->add('description',TextareaType::class, [
                'label' => "Description de votre demande :  ",
        'attr' => [
            'class' => 'input is-warning',
            'style' => 'width: 1000px ; height: 100px',
            'placeholder' => "Veuillez décrire votre demande"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez une description',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => "La description de la demande doit faire au minimum 10 caractères",
                        'max' => 200,
                        'maxMessage' => "La description de la demande doit faire au maximum 200 caractères",
                        // max length allowed by Symfony for security reasons
                    ]),
                ]])
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
