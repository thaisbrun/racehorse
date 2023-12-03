<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Favoris;
use App\Entity\Utilisateur;
use App\Form\AnnonceType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Form\UtilisateurType;
use App\Repository\AnnonceRepository;
use App\Repository\FavorisRepository;
use App\Repository\UtilisateurRepository;
use App\Security\AuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\Curl\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SecurityController extends AbstractController
{
    #[Route('security/{idutilisateur}/edit', name: 'security/app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $userRepository): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($utilisateur, true);

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('security/edit.html.twig', [
            'userEditForm' => $form,
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route(path: 'security/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
         return $this->redirectToRoute('security/app_viewProfil');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('security/mes_annonces/{idutilisateur}', name: 'security/mes_annonces', methods: ['GET'])]
    public function show_by_id_utilisateur(Utilisateur $user, AnnonceRepository $annonceRepository): Response
    {
        $idutilisateur = $user->getIdutilisateur();
        $listAnnonces = $annonceRepository->findBy(array('idutilisateurannonce' => $idutilisateur));
        return $this->render('security/mes_annonces.html.twig', [
            'listAnnonces' => $listAnnonces,
        ]);
    }
    #[Route('security/mes_favoris/{idutilisateur}', name: 'security/mes_favoris', methods: ['GET'])]
    public function show_fav(Utilisateur $user, FavorisRepository $favorisRepository, AnnonceRepository $annonceRepository): Response
    {
        //Trouver les annonces qui ont le meme idAnnonceUtilisateur que l'utilisateur connecté
        $idutilisateur = $user->getIdutilisateur();
        $listFavoris = $favorisRepository->findBy(array('idutilisateurfav' => $idutilisateur));
        return $this->render('security/mes_favoris.html.twig', [
            'listFavoris' => $listFavoris,
        ]);
    }
    #[Route(path: 'security/viewProfil', name: 'security/app_viewProfil')]
    public function viewProfil(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/viewProfil.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    #[Route(path: 'security/forgottenPassword', name: 'security/forgottenPassword')]
    public function forgottenPassword(Request $request, UtilisateurRepository $utilisateurRepository,
    TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager): Response
    {
        $formPassword = $this->createForm(ResetPasswordRequestFormType::class);
        $formPassword->handleRequest($request);
        if($formPassword->isSubmitted() && $formPassword->isValid())
        {
            $user = $utilisateurRepository->findOneBy(array('mail' =>$formPassword->get('mail')->getData()));

        $this->addFlash('danger','Un problème est survenu');
        return $this->redirectToRoute('security/forgottenPassword');

        }
        return $this->render('security/reset_password_request.html.twig',
            [
                'formPassword' => $formPassword->createView(),
            ]);

    }

    #[Route(path: 'security/logout', name: 'security/app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path:'security/delete/{idutilisateur}', name: 'security/app_delete', methods: ['GET','POST'])]
    public function delete(Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurRepository->remove($utilisateur, true);

        return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
    }

}
