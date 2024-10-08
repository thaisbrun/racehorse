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
        //Ici je permets de filtrer les annonces en fonction de leur type d'annonce.
        $annoncesVente = $annonceRepository->FindBy(array('typeA' => 1));
        $annoncesLocation = $annonceRepository->FindBy(array('typeA' => 2));
        $annoncesDP = $annonceRepository->FindBy(array('typeA' => 3));

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
    #[Route('annonce/{id}/favori', name: 'fav', methods: ['GET', 'POST'])]
    public function favoris(Annonce $annonce, EntityManagerInterface $entityManager, FavorisRepository $favRepository) : Response {

        //Je recupère le user connecté
        $utilisateur = $this->getUser();

        //Si un utilisateur n'est pas connecté alors je n'autorise pas le favori
        if(!$utilisateur) return $this->json([
            'code' => 403,
            'message' => "Non autorisé"
        ],403);
        //Si l'annonce a déjà été mise en favori par cet utilisateur
        if($annonce->isLikedByUser($utilisateur)){
            //Je trouve le favori correspondant
            $favori = $favRepository->findOneBy([
                'annoncefav' => $annonce,
                'utilisateurfav' => $utilisateur
            ]);
            //Et je supprime le favori
            $entityManager->remove($favori);
            $entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Favori supprimé',
                'favoris' => $favRepository->count(['annoncefav' => $annonce])
            ],200);
        }
        //Si l'annonce n'a pas été déjà mise en favori par ce user je crée un nouveau favori
        $favori = new Favoris();
        $favori->setAnnoncefav($annonce)
            ->setUtilisateurfav($utilisateur)
            ->setDatecreation(new \DateTime());
        $entityManager->persist($favori);
        $entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Favori ajouté',
            'favoris' => $favRepository->count(['annoncefav' => $annonce])
        ],200);
    }
    #[Route('annonce/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnonceRepository $annonceRepository,
                        EquideRepository $equideRepository, EntityManagerInterface $entityManager): Response
    {
        //Si le user n'est pas connecté message d'erreur s'affiche
        if ($this->getUser() == null) {
            $this->addFlash('error', 'Vous devez vous connecter pour pouvoir ajouter une annonce ');
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        } else {
            $annonce = new Annonce();
            $equide = new Equide();
            $annonceForm = $this->createForm(AnnonceType::class, $annonce);
            $annonceForm->handleRequest($request);
            if ($annonceForm->isSubmitted() && $annonceForm->isValid()) {
                $images = $annonceForm->get('images')->getData();
                // Ajoutez chaque image à la collection d'images de l'annonce
                foreach ($images as $image) {
                    // Faites ce que vous devez pour gérer le téléchargement et le stockage des images,
                    // puis ajoutez-les à la collection d'images de l'annonce
                    $annonce->addImage($image);
                }
                $equide = $annonceForm->get('idequidea')->getData();
                $equide->setProprio($this->getUser());
                if ($annonce->getPrix() < 0) {
                    $this->addFlash("erreur", "Le prix ne peut pas être inférieur");
                } elseif ($equide->getTaille() < 0) {
                    $this->addFlash("erreur", "La taille ne peut pas être inférieure");
                } else {
                    $entityManager->persist($equide);
                    $entityManager->flush($equide);

                    $equideRepository->save($equide, true);

                    $annonce->setDatecreation(new \DateTime());
                    $annonce->setUtilisateurAnnonce($this->getUser());
                    $annonce->setEquide($equide);
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
    #[Route('annonce/show_by_type/{typeA}', name: 'app_annonce_show_by_type', methods: ['GET'])]
    public function show_by_type(int $typeA, AnnonceRepository $annonceRepository): Response
    {
        //Je filtre les annonces avec le type d'annonce sélectionné
        $listAnnonces = $annonceRepository->FindBy(array('typeA' => $typeA));
        return $this->render('annonce/show_by_type.html.twig', [
            'listAnnonces' => $listAnnonces,
        ]);
    }
    #[Route('annonce/show/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
    #[Route('annonce/edit/{id}', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository,
                         EquideRepository $equideRepository, EntityManagerInterface $entityManager): Response
    {
        //Je vérifie les droits d'accès de l'utilisateur connecté
        if ($this->getUser() !== $annonce->getUtilisateurAnnonce() || $this->getUser() == null) {
            $this->addFlash('error', "Accès non autorisé");
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        } else {

            $form = $this->createForm(AnnonceType::class, $annonce);
            $equide = $annonce->getEquide();
            $form->handleRequest($request);

            //Ajout des images dans l'édition
            if ($form->isSubmitted() && $form->isValid()) {
                $images = $form->get('images')->getData();
                // Ajoutez chaque image à la collection d'images de l'annonce
                foreach ($images as $image) {
                    // Faites ce que vous devez pour gérer le téléchargement et le stockage des images,
                    // puis ajoutez-les à la collection d'images de l'annonce
                    $annonce->addImage($image);
                }

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
    #[Route('annonce/delete/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            //Je m'assure que la personne connectée est l'auteur de l'annonce
            if($this->getUser() !== $annonce->getUtilisateurAnnonce() || $this->getUser() == null){
                $this->addFlash('error', 'Accès non autorisé');
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }else{
            //Je supprime l'annonce
            $entityManager->remove($annonce, true);
            $entityManager->flush();
            }
        }
        return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
    }
}
