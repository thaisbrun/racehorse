<?php

namespace App\Controller;


use App\Entity\Utilisateur;
use App\Form\ResetPasswordRequestFormType;
use App\Form\UtilisateurType;
use App\Repository\AnnonceRepository;
use App\Repository\FavorisRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

//Ce controller est lié à la page de connexion et de profil, expliquant les informations principales de l'utilisateur.
class SecurityController extends AbstractController
{
    #[Route('security/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $user, UtilisateurRepository $userRepository): Response
    {
        if ($this->getUser() !== $user || $this->getUser() == null) {
            $this->addFlash('error', "Accès non autorisé");
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        } else {
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('security/edit.html.twig', [
            'userEditForm' => $form,
            'utilisateur' => $user,
        ]);
    }
    }
    #[Route(path: 'security/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
         return $this->redirectToRoute('app_security_viewprofil');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    #[Route('security/mes_annonces/{id}', name: 'app_security_mes_annonces', methods: ['GET'])]
    public function show_by_utilisateur(Utilisateur $user, AnnonceRepository $annonceRepository): Response
    {
        if ($this->getUser() !== $user || $this->getUser() == null) {
            $this->addFlash('error', "Accès non autorisé");
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        } else {
        $listAnnonces = $annonceRepository->findBy(array('utilisateurannonce' => $user));
        return $this->render('security/mes_annonces.html.twig', [
            'listAnnonces' => $listAnnonces,
        ]);
    }}

    #[Route(path: 'security/viewProfil', name: 'app_security_viewprofil')]
    public function viewProfil(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        UtilisateurRepository $userRepository, AnnonceRepository $annonceRepository, FavorisRepository $favorisRepository
    ): Response {
        // Vérification de l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Création du formulaire
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('app_security_viewprofil');
        }

        // Récupération des erreurs d'authentification
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Récupération des annonces de l'utilisateur
        $listAnnonces = $annonceRepository->findBy(array('utilisateurannonce' => $user));
        $favoris = $favorisRepository->findBy(array('utilisateurfav' => $user));
        return $this->render('security/viewProfil.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'userEditForm' => $form->createView(),
            'annonces' => $listAnnonces,
            'favoris' => $favoris,
            'user' => $user
        ]);
    }
    #[Route('/security/forgotten-password', name: 'app_security_forgottenpassword')]
    public function forgottenPassword(
        Request $request,
        MailerInterface $mailer,
        UtilisateurRepository $utilisateurRepository,
        EntityManagerInterface $em
    ): Response {
        $formPassword = $this->createForm(ResetPasswordRequestFormType::class);
        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $email = $formPassword->get('mail')->getData();
            $user = $utilisateurRepository->findOneBy(['mail' => $email]);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $user->setResetToken($token);
                $user->setResetTokenExpiresAt(new \DateTime('+1 hour'));
                $em->flush();

                $url = $this->generateUrl('app_security_reset_password', [
                    'token' => $token
                ], UrlGeneratorInterface::ABSOLUTE_URL);
                try {

                $emailMessage = (new Email())
                    ->from('no-reply@example.com')
                    ->to($user->getMail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->text("Bonjour,\n\nCliquez sur ce lien pour réinitialiser votre mot de passe :\n$url");

                $mailer->send($emailMessage);
                $this->addFlash('success', 'Un email de réinitialisation a été envoyé.');
                    return $this->redirectToRoute('app_login');
                }catch (\Exception $e){
                    $this->addFlash('error', $e->getMessage());
                }


            }

            $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'formPassword' => $formPassword->createView(),
        ]);
    }

    // src/Controller/TestMailController.php
    #[Route('/test-mail')]
    public function testMail(MailerInterface $mailer)
    {
        $email = (new \Symfony\Component\Mime\Email())
            ->from('no-reply@example.com')
            ->to('test@example.com')
            ->subject('Test Mailtrap')
            ->text('Ceci est un test');

        $mailer->send($email);

        return new Response('Mail envoyé');
    }

    #[Route('/security/reset-password/{token}', name: 'app_security_reset_password')]
    public function resetPassword(
        Request $request,
        string $token,
        UtilisateurRepository $repo,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $repo->findOneBy(['resetToken' => $token]);

        if (!$user || $user->getResetTokenExpiresAt() < new \DateTime()) {
            $this->addFlash('danger', 'Lien invalide ou expiré.');
            return $this->redirectToRoute('app_security_forgottenpassword');
        }

        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6])
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);
            $em->flush();

            $this->addFlash('success', 'Mot de passe réinitialisé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route(path: 'security/logout', name: 'app_security_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path:'security/delete/{id}', name: 'app_security_delete', methods: ['GET','POST'])]
    public function delete(Utilisateur $user, UtilisateurRepository $userRepository,TokenStorageInterface $tokenStorage, RequestStack $requestStack): Response
    {
        if ($this->getUser() !== $user || $this->getUser() === null) {
            $this->addFlash('error', "Accès non autorisé");
        } else {
            // Déconnexion propre
            $tokenStorage->setToken(null);
            $requestStack->getSession()->invalidate();

            $userRepository->remove($user, true);
            $this->addFlash('successInIndex', "Compte supprimé avec succès");
        }


        return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
    }

}
