<?php

namespace App\Controller;

use App\Entity\Requete;
use App\Form\RequeteType;
use App\Repository\AnnonceRepository;
use App\Repository\RequeteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class RequeteController extends AbstractController
{
    #[Route('/requete', name: 'app_requete_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser() == null){
            $this->addFlash('error', 'Vous devez vous connecter pour pouvoir utiliser le formulaire de contact ');
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }else {
        $requete = new Requete();
        $form = $this->createForm(RequeteType::class, $requete);
        $user = $this->getUser();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requete->setIdauteurrequete($user);
            $entityManager->persist($requete);
            $entityManager->flush();
            $this->addFlash('success', 'Votre demande a été envoyée, votre réponse sera envoyé par mail. ');
            return $this->redirectToRoute('app_requete_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('requete/new.html.twig', [
            'requete' => $requete,
            'form' => $form,
        ]);
        }
    }
}
