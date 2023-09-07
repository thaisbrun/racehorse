<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\MyClassRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
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
    #[Route('/{idutilisateur}', name: 'app_delete', methods: ['POST'])]
    public function remove(Request $request, Utilisateur $user, UtilisateurRepository $utilisateurRepository): Response
    {
        $id = $this->getUser()->getUserIdentifier();
        if ($this->isCsrfTokenValid('delete'.$user->getIdUtilisateur(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);

    }
}
