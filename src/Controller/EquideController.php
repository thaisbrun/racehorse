<?php

namespace App\Controller;

use App\Entity\Equide;
use App\Form\EquideType;
use App\Repository\DepartementRepository;
use App\Repository\EquideRepository;
use App\Repository\RaceRepository;
use App\Repository\RobeRepository;
use App\Repository\TypeEquideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equide')]
class EquideController extends AbstractController
{
    #[Route('/', name: 'app_equide_index', methods: ['GET'])]
    public function index(EquideRepository $equideRepository): Response
    {
        return $this->render('equide/index.html.twig', [
            'equides' => $equideRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_equide_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RaceRepository $raceRepository, RobeRepository $robeRepository,
    TypeEquideRepository $typeEquideRepository, DepartementRepository $departementRepository): Response
    {
        $equide = new Equide();

        $form = $this->createForm(EquideType::class, $equide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equide->setIdproprio($this->getUser());
            $entityManager->persist($equide);
            $entityManager->flush();
        }

        return $this->renderForm('equide/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{idequide}', name: 'app_equide_show', methods: ['GET'])]
    public function show(Equide $equide): Response
    {
        return $this->render('equide/show.html.twig', [
            'equide' => $equide,
        ]);
    }

    #[Route('/{idequide}/edit', name: 'app_equide_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equide $equide, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquideType::class, $equide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equide/edit.html.twig', [
            'equide' => $equide,
            'form' => $form,
        ]);
    }

    #[Route('/{idequide}', name: 'app_equide_delete', methods: ['POST'])]
    public function delete(Request $request, Equide $equide, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equide->getIdequide(), $request->request->get('_token'))) {
            $entityManager->remove($equide);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equide_index', [], Response::HTTP_SEE_OTHER);
    }
}
