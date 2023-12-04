<?php

namespace App\Controller;
use App\Entity\Favoris;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Entity\Equide;
use App\Form\AnnonceType;
use App\Repository\EquideRepository;
use App\Repository\AnnonceRepository;
use App\Repository\TypeAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


#[Route('/')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
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
    public function all_annonces(AnnonceRepository $annonceRepository, TypeAnnonceRepository $typeAnnonceRepository, Request $request): Response
    {
        //On récupère des filtres
        $filters = $request->get("listTypeAnnonces");

       $listAnnonces = $annonceRepository->getFiltersAnnonces($filters);
        $listTypeAnnonces = $typeAnnonceRepository->findAll();

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
        ]);
    }

    /**
     * @param Annonce $annonce
     * @param EntityManagerInterface $entityManager
     * @param AnnonceRepository $annonceRepository
     * @return void
     */
    #[Route('annonce/{idannonce}/favori', name: 'fav', methods: ['GET', 'POST'])]
    public function favoris(Annonce $annonce, EntityManagerInterface $entityManager, FavorisRepository $favRepository) : Response {

        $utilisateur = $this->getUser();

        if(!$utilisateur) return $this->json([
            'code' => 403,
            'message' => "Non autorisé"
        ],403);
        if($annonce->isLikedByUser($utilisateur)){
            $favori = $favRepository->findOneBy([
                'idannoncefav' => $annonce,
                'idutilisateurfav' => $utilisateur
            ]);
            $entityManager->remove($favori);
            $entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Favori supprimé',
                'favoris' => $favRepository->count(['idannoncefav' => $annonce])
            ],200);
        }
        $favori = new Favoris();
        $favori->setIdannoncefav($annonce)
            ->setIdutilisateurfav($utilisateur)
            ->setDatecreation(new \DateTime());
        $entityManager->persist($favori);
        $entityManager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'Favori ajouté',
            'favoris' => $favRepository->count(['idannoncefav' => $annonce])
        ],200);
    }
    #[Route('annonce/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnonceRepository $annonceRepository,
                        EquideRepository $equideRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() == null) {
            $this->addFlash('error', 'Vous devez vous connecter pour pouvoir ajouter une annonce ');
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        } else {
            $annonce = new Annonce();
            $equide = new Equide();
            $annonceForm = $this->createForm(AnnonceType::class, $annonce);
            $annonceForm->handleRequest($request);
            if ($annonceForm->isSubmitted() && $annonceForm->isValid()) {
                $equide = $annonceForm->get('idequidea')->getData();
                $equide->setIdproprio($this->getUser());
                if ($annonce->getPrix() < 0) {
                    $this->addFlash("erreur", "Le prix ne peut pas être inférieur");
                } elseif ($equide->getTaille() < 0) {
                    $this->addFlash("erreur", "La taille ne peut pas être inférieure");
                } else {
                    $entityManager->persist($equide);
                    $entityManager->flush($equide);

                    $equideRepository->save($equide, true);

                    $annonce->setDatecreation(new \DateTime());
                    $annonce->setIdutilisateurannonce($this->getUser());
                    $annonce->setIdequidea($equide);
                    $annonceRepository->save($annonce, true);
                    return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->renderForm('annonce/new.html.twig', [
                'annonce' => $annonce,
                'idequidea' => $equide,
                'annonceForm' => $annonceForm,
            ]);
        }
    }
    #[Route('annonce/show_by_type_annonce/{idtypea}', name: 'app_annonce_show_by_type_annonce', methods: ['GET'])]
    public function show_by_type_annonce(int $idtypea, AnnonceRepository $annonceRepository): Response
    {
        $listAnnonces = $annonceRepository->FindBy(array('idtypea' => $idtypea));
        return $this->render('annonce/show_by_type_annonce.html.twig', [
            'listAnnonces' => $listAnnonces,
        ]);
    }
    #[Route('annonce/show/{idannonce}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
    #[Route('annonce/edit/{idannonce}', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository,
                         EquideRepository $equideRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $annonce->getIdutilisateurannonce()) {
            $this->addFlash('error', "Accès non autorisé");
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        } else {
            $form = $this->createForm(AnnonceType::class, $annonce);
            $equide = $annonce->getIdequidea();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($equide);
                $entityManager->flush($equide);
                $equideRepository->save($equide, true);
                $annonceRepository->save($annonce, true);

                return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('annonce/edit.html.twig', [
                'annonceForm' => $form,
            ]);
        }
    }
    #[Route('annonce/delete/{idannonce}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getIdannonce(), $request->request->get('_token'))) {
            $entityManager->remove($annonce, true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
    }

}
