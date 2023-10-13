<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Utilisateur;
use App\Form\FavorisType;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/favoris')]
class FavorisController extends AbstractController
{
    #[Route('/', name: 'app_favoris_index', methods: ['GET'])]
    public function index(FavorisRepository $favorisRepository): Response
    {
        return $this->render('favoris/index.html.twig', [
            'favoris' => $favorisRepository->findAll(),
        ]);
    }

    #[Route('favoris/new', name: 'app_favoris_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $favori = new Favoris();

            $entityManager->persist($favori);
            $entityManager->flush();
            dd($favori);
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idutilisateurfav}', name: 'app_favoris_show', methods: ['GET'])]
    public function show(Favoris $favori): Response
    {
        return $this->render('favoris/show.html.twig', [
            'favori' => $favori,
        ]);
    }

    #[Route('/{idutilisateurfav}/edit', name: 'app_favoris_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Favoris $favori, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FavorisType::class, $favori);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('favoris/edit.html.twig', [
            'favori' => $favori,
            'form' => $form,
        ]);
    }

    #[Route('/{idutilisateurfav}', name: 'app_favoris_delete', methods: ['POST'])]
    public function delete(Request $request, Favoris $favori, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favori->getIdutilisateurfav(), $request->request->get('_token'))) {
            $entityManager->remove($favori);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }
}
