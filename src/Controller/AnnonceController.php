<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Form\EquideType;
use App\Repository\TypeEquideRepository;
use http\Client\Curl\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Annonce;
use App\Entity\Equide;
use App\Form\AnnonceType;
use App\Repository\DepartementRepository;
use App\Repository\EquideRepository;
use App\Repository\ImageRepository;
use App\Repository\AnnonceRepository;
use App\Repository\RaceRepository;
use App\Repository\RegionRepository;
use App\Repository\RobeRepository;
use App\Repository\TypeAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


#[Route('/')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository, ImageRepository $imageRepository): Response
    {
        $annoncesVente = $annonceRepository->FindBy(array('idtypea' => 1));
        $annoncesLocation = $annonceRepository->FindBy(array('idtypea' => 2));
        $annoncesDP = $annonceRepository->FindBy(array('idtypea' => 3));
        return $this->render('annonce/index.html.twig', [
            'annoncesVente' => $annoncesVente,
            'annoncesLocation' => $annoncesLocation,
            'annoncesDP' => $annoncesDP,
        ]);
    }
    #[Route('/annonce/all_annonces', name: 'app_annonce_all_annonces', methods: ['GET'])]
    public function all_annonces(AnnonceRepository $annonceRepository, TypeAnnonceRepository $typeAnnonceRepository, DepartementRepository $departementRepository,
                                 RobeRepository $robeRepository, RaceRepository $raceRepository, Request $request): Response
    {
        //On récupère des filtres
        $filters = $request->get("listTypeAnnonces");
        $departements = $request->get("listDepartements");
        $races = $request->get("listRaces");
        $robes = $request->get("listRobes");

       $listAnnonces = $annonceRepository->getFiltersAnnonces($filters, $departements, $races, $robes);
        $listTypeAnnonces = $typeAnnonceRepository->findAll();
        $listDepartements = $departementRepository->findAll();
        $listRobes = $robeRepository->findAll();
        $listRaces = $raceRepository->findAll();

        //On vérifie si y a une requête Ajax
        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('annonce/_content.html.twig', [
                    'listAnnonces' => $listAnnonces])
            ]);
        }

        return $this->render('annonce/all_annonces.html.twig', [
            'listAnnonces' => $listAnnonces,
            'listTypeAnnonces' => $listTypeAnnonces,
            'listDepartements' => $listDepartements,
            'listRobes' => $listRobes,
            'listRaces' => $listRaces
        ]);
    }
    #[Route('annonce/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnonceRepository $annonceRepository, TypeAnnonceRepository $typeAnnonceRepository,
    Equide $equide): Response
    {
        $annonce = new Annonce();
        $user = $this->getUser();
        $listTypeAnnonce = $typeAnnonceRepository->findAll();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setIdequidea($equide);
            $annonce->setIdutilisateurannonce($user);
           $annonceRepository->save($annonce, true);
           return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'listTypeAnnonce' => $listTypeAnnonce,
            'form' => $form,
        ]);
    }
    #[Route('annonce/show_by_type_annonce/{idtypea}', name: 'show_by_type_annonce', methods: ['GET'])]
    public function show_by_type_annonce(int $idtypea, AnnonceRepository $annonceRepository): Response
    {
        $listAnnonces = $annonceRepository->FindBy(array('idtypea' => $idtypea));
        return $this->render('annonce/show_by_type_annonce.html.twig', [
            'listAnnonces' => $listAnnonces,
        ]);
    }
    #[Route('annonce/show/{idannonce}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce, ImageRepository $imageRepository,EquideRepository $equideRepository, DepartementRepository $departementRepository, RegionRepository $regionRepository): Response
    {
        $idEquide = $annonce->getIdequidea()->getIdequide();
        $equide = $equideRepository->findOneBy(array('idequide' => $idEquide));
        $listImages = $imageRepository->findBy(array('idannonceimage' => $annonce->getIdannonce()));
        $race = $annonce->getIdequidea()->getRace();
        $robe = $annonce->getIdequidea()->getRobe();

        $idEquideDep = $annonce->getIdequidea()->getIddep();
        $departement = $departementRepository->findOneBy(array('iddepartement' => $idEquideDep));

        $idEquideRegion = $annonce->getIdequidea()->getIddep()->getIdregiondep();
        $region = $regionRepository->findOneBy(array('idregion' => $idEquideRegion));

        $user = $annonce->getIdequidea()->getIdproprio();
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'equide' => $equide,
            'race' => $race,
            'robe' => $robe,
            'departement' => $departement,
            'region' => $region,
            'user' => $user,
            'listImages' => $listImages,
        ]);
    }

    #[Route('annonce/edit/{idannonce}', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository, EquideRepository $equideRepository, TypeAnnonceRepository $typeAnnonceRepository): Response
    {
        $listTypeAnnonce = $typeAnnonceRepository->findAll();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $idEquide = $annonce->getIdequidea()->getIdequide();
        $equide = $equideRepository->findOneBy(array('idequide' => $idEquide));
        $form->handleRequest($request);
        $formEquide = $this->createForm(EquideType::class, $equide);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'equide' => $equide,
            'form' => $form,
            'formEquide' => $formEquide,
            'listTypeAnnonce' => $listTypeAnnonce,
        ]);
    }

    #[Route('annonce/delete/{idannonce}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $myClassRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getIdannonce(), $request->request->get('_token'))) {
            $myClassRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
    }


}
