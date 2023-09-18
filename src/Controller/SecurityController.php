<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Utilisateur;
use App\Form\AnnonceType;
use App\Form\RegistrationFormType;
use App\Form\UtilisateurType;
use App\Repository\MyClassRepository;
use App\Repository\UtilisateurRepository;
use App\Security\AuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\Curl\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
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
         return $this->redirectToRoute('homepage');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('security/mes_annonces/{idutilisateur}', name: 'security/mes_annonces', methods: ['GET'])]
    public function show_by_id_utilisateur(Utilisateur $user, MyClassRepository $myClassRepository): Response
    {
        $idutilisateur = $user->getIdutilisateur();
        $listAnnonces = $myClassRepository->findByUser($idutilisateur);
        return $this->render('security/mes_annonces.html.twig', [
            'listAnnonces' => $listAnnonces,
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
