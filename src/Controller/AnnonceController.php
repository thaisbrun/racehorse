<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Utilisateur;
use App\Form\EquideType;
use App\Repository\TypeEquideRepository;
use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\Validator\Constraints\DateTime;


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
    public function new(Request $request, AnnonceRepository $annonceRepository,
                        EquideRepository $equideRepository, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonce();
        $equide = new Equide();
        $annonceForm = $this->createForm(AnnonceType::class, $annonce);
        $annonceForm->handleRequest($request);
        if ($annonceForm->isSubmitted() && $annonceForm->isValid()) {
            $equide = $annonceForm->get('equide')->getData();
            $equide->setIdproprio($this->getUser());

            $entityManager->persist($equide);
            $entityManager->flush($equide);

            $equideRepository->save($equide, true);

            $annonce->setDatecreation(new \DateTime());
            $annonce->setIdutilisateurannonce($this->getUser());
            $annonce->setIdequidea($equide);
            $annonceRepository->save($annonce, true);
           return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'equide' => $equide,
            'annonceForm' => $annonceForm,
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
        $annonce->setListImages($imageRepository->findBy(array('idannonceimage' => $annonce->getIdannonce())));
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('annonce/edit/{idannonce}', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository, EquideRepository $equideRepository, TypeAnnonceRepository $typeAnnonceRepository): Response
    {
        $listTypeAnnonce = $typeAnnonceRepository->findAll();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
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
