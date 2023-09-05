<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Equide;
use App\Entity\Typeannonce;
use App\Form\AnnonceType;
use App\Repository\DepartementRepository;
use App\Repository\EquideRepository;
use App\Repository\MyClassRepository;
use App\Repository\RaceRepository;
use App\Repository\RegionRepository;
use App\Repository\RobeRepository;
use App\Repository\TypeAnnonceRepository;
use App\Repository\UtilisateurRepository;
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
    public function new(Request $request, MyClassRepository $myClassRepository, TypeAnnonceRepository $typeAnnonceRepository, RaceRepository $raceRepository,
    RobeRepository $robeRepository): Response
    {
        $annonce = new Annonce();
        $listTypeAnnonce = $typeAnnonceRepository->findAll();
        $listRaces = $raceRepository->findAll();
        $listRobes = $robeRepository->findAll();

        // $equide = new Equide();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myClassRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'listTypeAnnonce' => $listTypeAnnonce,
            'listRaces' => $listRaces,
            'listRobes' => $listRobes,

            // 'equide' => $equide,
            'form' => $form,
        ]);
    }

    #[Route('/{idannonce}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce, EquideRepository $equideRepository, RaceRepository $raceRepository, RobeRepository $robeRepository,
    DepartementRepository $departementRepository, RegionRepository $regionRepository, UtilisateurRepository $userRepository): Response
    {
        $idAnnonce = $annonce->getIdannonce();
        $equide = $equideRepository->findByIdAnnonce($idAnnonce);

        $idEquideRace = $equide->getRace();
        $idEquideRobe = $equide->getRobe();

        $race = $raceRepository->findByIdEquide($idEquideRace);
        $robe = $robeRepository->findByIdEquide($idEquideRobe);

        $idEquideDep = $equide->getIddep();
        $departement = $departementRepository->findByIdDepEquide($idEquideDep);

        $idDepartement = $departement->getIdregiondep();
        $region = $regionRepository->findByIdDepartement($idDepartement);

        $idUtil = $equide->getIdproprio();
        $user = $userRepository->findByIdProprio($idUtil);
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'equide' => $equide,
            'race' => $race,
            'robe' => $robe,
            'departement' => $departement,
            'region' => $region,
            'user' => $user,
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
