<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Form\EquideType;
use App\Repository\TypeEquideRepository;
use http\Client\Curl\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Annonce;
use App\Entity\Equide;
use App\Entity\Typeannonce;
use App\Form\AnnonceType;
use App\Repository\DepartementRepository;
use App\Repository\EquideRepository;
use App\Repository\ImageRepository;
use App\Repository\MyClassRepository;
use App\Repository\RaceRepository;
use App\Repository\RegionRepository;
use App\Repository\RobeRepository;
use App\Repository\TypeAnnonceRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


#[Route('/')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(MyClassRepository $myClassRepository,ImageRepository $imageRepository): Response
    {
        $annoncesVente = $myClassRepository->findByTypeAnnonce(1);
        $annoncesLocation = $myClassRepository->findByTypeAnnonce(2);
        $annoncesDP = $myClassRepository->findByTypeAnnonce(3);
        return $this->render('annonce/index.html.twig', [
            'annoncesVente' => $annoncesVente,
            'annoncesLocation' => $annoncesLocation,
            'annoncesDP' => $annoncesDP,
        ]);
    }
    #[Route('annonce/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MyClassRepository $myClassRepository, TypeAnnonceRepository $typeAnnonceRepository, RaceRepository $raceRepository,
    RobeRepository $robeRepository, EquideRepository $equideRepository, TypeEquideRepository $typeEquideRepository, DepartementRepository $departementRepository): Response
    {
        $annonce = new Annonce();
        $equide = new Equide();

        $user = $this->getUser();
        $listTypeAnnonce = $typeAnnonceRepository->findAll();
        $listRaces = $raceRepository->findAll();
        $listRobes = $robeRepository->findAll();
        $listTypeEquide = $typeEquideRepository->findAll();
        $listDepartements = $departementRepository->findAll();

        $idUser = $user->getIdutilisateur();
        $annonce->setIdutilisateurannonce($idUser);

        $formEquide = $this->createForm(EquideType::class, $equide);
       // $formEquide = $this->handleRequest($request);
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $equideRepository->save($equide,true);
            $myClassRepository->save($annonce, true);

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'listTypeAnnonce' => $listTypeAnnonce,
            'listRaces' => $listRaces,
            'listRobes' => $listRobes,
            'listTypeEquide' => $listTypeEquide,
            'listDepartements' => $listDepartements,
            'form' => $form,
            'formEquide' => $formEquide,
        ]);
    }
    #[Route('annonce/show_by_type_annonce/{idtypea}', name: 'show_by_type_annonce', methods: ['GET'])]
    public function show_by_type_annonce(int $idtypea, MyClassRepository $myClassRepository): Response
    {
        $listAnnonces = $myClassRepository->findByTypeAnnonce($idtypea);
        return $this->render('annonce/show_by_type_annonce.html.twig', [
            'listAnnonces' => $listAnnonces,
        ]);
    }
    #[Route('annonce/show/{idannonce}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce, EquideRepository $equideRepository, RaceRepository $raceRepository, RobeRepository $robeRepository,
    DepartementRepository $departementRepository, RegionRepository $regionRepository, UtilisateurRepository $userRepository): Response
    {
        $idAnnonce = $annonce->getIdannonce();
        $equide = $equideRepository->findByIdAnnonce($idAnnonce);

        $idEquideRace = $equide->getRace()->getId();
        $idEquideRobe = $equide->getRobe()->getId();


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

    #[Route('annonce/edit/{idannonce}', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, MyClassRepository $myClassRepository, TypeAnnonceRepository $typeAnnonceRepository): Response
    {
        $listTypeAnnonce = $typeAnnonceRepository->findAll();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myClassRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
            'listTypeAnnonce' => $listTypeAnnonce,
        ]);
    }

    #[Route('annonce/delete/{idannonce}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, MyClassRepository $myClassRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getIdannonce(), $request->request->get('_token'))) {
            $myClassRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }


}
