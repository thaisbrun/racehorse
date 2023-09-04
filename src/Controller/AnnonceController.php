<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Equide;
use App\Entity\Typeannonce;
use App\Form\AnnonceType;
use App\Repository\MyClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(MyClassRepository $myClassRepository): Response
    {
        $annoncesVente = $myClassRepository->findByTypeAnnonce(1);
        $annoncesLocation = $myClassRepository->findByTypeAnnonce(2);
        $annoncesDP = $myClassRepository->findByTypeAnnonce(3);
        return $this->render('annonce/index.html.twig', [
            'annoncesVente' => $annoncesVente,
            'annoncesLocation' => $annoncesLocation,
            'annoncesDP' => $annoncesDP
        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MyClassRepository $myClassRepository): Response
    {
        $annonce = new Annonce();
        $listTypeAnnonce = [];
        foreach($listTypeAnnonce as $typeAnnonce){
            array_push($typeAnnonce);
    }
        $equide = new Equide();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myClassRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'listTypeAnnonce' => $listTypeAnnonce,
            'equide' => $equide,
            'form' => $form,
        ]);
    }

    #[Route('/{idannonce}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

 /*   #[Route('/{idtypea}', name: 'app_annonce_show_by_type_annonce', methods: ['GET'])]
    public function showByTypeA(Annonce $annonce): Response
    {
        return $this->render('annonce/show_by_type_annonce.html.twig', [
            'annonce' => $annonce,
        ]);
    }*/
    #[Route('/{idannonce}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, MyClassRepository $myClassRepository): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myClassRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{idannonce}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, MyClassRepository $myClassRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getIdannonce(), $request->request->get('_token'))) {
            $myClassRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
