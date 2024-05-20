<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\AuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

//Ce controller est lié à la page d'inscription du site.
class RegistrationController extends AbstractController
{
    #[Route('security/register', name: 'app_registration_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AuthAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        //Si le user est connecté alors je le redirige vers son profil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_security_viewprofil');
        }

        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
