<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\AnnonceRepository;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image')]
class ImageController extends AbstractController
{
    #[Route('/', name: 'app_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AnnonceRepository $annonceRepository): JsonResponse
    {
        // Récupérer l'id de l'annonce dans le POST (que tu passes via JS)
        $annonceId = $request->request->get('annonce_id');
        if (!$annonceId) {
            return new JsonResponse(['success' => false, 'message' => 'Annonce manquante'], 400);
        }
        $annonce = $annonceRepository->find($annonceId);
        if (!$annonce) {
            return new JsonResponse(['success' => false, 'message' => 'Annonce introuvable'], 400);
        }

        // Récupérer les fichiers uploadés
        $files = $request->files->get('annonce_form')['images'] ?? [];
        if (empty($files)) {
            return new JsonResponse(['success' => false, 'message' => 'Aucun fichier reçu'], 400);
        }

        $uploadedImages = [];
        foreach ($files as $uploadedFile) {
            if ($uploadedFile instanceof UploadedFile) {
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $image = new Image();
                $image->setLienimage('imgAnnonce/' . $newFilename);
                $image->setAnnonceImage($annonce);

                $entityManager->persist($image);
                $uploadedImages[] = [
                    'id' => null, // pas encore d’ID avant flush
                    'path' => '/imgAnnonce/' . $newFilename,
                ];
            }
        }

        $entityManager->flush();

        // Après flush, on récupère les IDs générés
        foreach ($uploadedImages as $key => $img) {
            $uploadedImages[$key]['id'] = $image->getId();
        }

        return new JsonResponse([
            'success' => true,
            'images' => $uploadedImages
        ]);
    }



    #[Route('/{id}', name: 'app_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return new JsonResponse(['success' => true]);
    }
}
