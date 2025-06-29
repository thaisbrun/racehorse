<?php

namespace App\Controller;
use App\Entity\Favoris;
use App\Entity\Image;
use App\Repository\DepartementRepository;
use App\Repository\FavorisRepository;
use App\Repository\RaceRepository;
use App\Repository\RobeRepository;
use App\Repository\TypeEquideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    #[Route('/annonce/all_annonces', name: 'app_annonce_all_annonces', methods: ['GET', 'POST'])]
    public function all_annonces(AnnonceRepository $annonceRepository,
                                 TypeAnnonceRepository $typeAnnonceRepository,
                                 RaceRepository $raceRepository,
                                 RobeRepository $robeRepository,
                                 DepartementRepository $departementRepository,
                                 TypeEquideRepository $typeequideRepository,
                                 Request $request): Response
    {
        // Récupération brute
        $rawSelectedTypes = $request->get('types', []);
        if (!is_array($rawSelectedTypes)) {
            $rawSelectedTypes = [$rawSelectedTypes];
        }
        $selectedTypes = array_map('intval', $rawSelectedTypes);

        // Construction des filtres
        $filters = [
            'types' => $selectedTypes,
            'race' => $request->get('race'),
            'robe' => $request->get('robe'),
            'ageMin' => $request->get('ageMin'),
            'ageMax' => $request->get('ageMax'),
            'departement' => $request->get('departement'),
            'typeEquide' => $request->get('typeEquide'),
        ];

        // Appels aux repositories
        $listAnnonces = $annonceRepository->getFiltersAnnonces($filters);
        $listTypeAnnonces = $typeAnnonceRepository->findAll();
        $listRobes = $robeRepository->findAll();
        $listRaces = $raceRepository->findAll();
        $listTypeEquides = $typeequideRepository->findAll();
        $listDepartements = $departementRepository->findAll();

        // Ajax ?
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('annonce/_content.html.twig', [
                    'listAnnonces' => $listAnnonces
                ])
            ]);
        }

        return $this->render('annonce/all_annonces.html.twig', [
            'listAnnonces' => $listAnnonces,
            'listTypeAnnonces' => $listTypeAnnonces,
            'selectedTypes' => $selectedTypes,
            'races' => $listRaces,
            'robes' => $listRobes,
            'departements' => $listDepartements,
            'typesEquides' => $listTypeEquides,
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
    public function new(
        Request $request,
        AnnonceRepository $annonceRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérification de l'authentification
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez vous connecter pour pouvoir ajouter une annonce');
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        $annonce = new Annonce();
        $equide = new Equide();

        // Associer l'équidé à l'annonce dès le début


        $annonceForm = $this->createForm(AnnonceType::class, $annonce);
        $annonceForm->handleRequest($request);

        if ($annonceForm->isSubmitted() && $annonceForm->isValid()) {
            $equide = $annonceForm->get('equide')->getData();

            // Validation métier
            $validationErrors = $this->validateBusinessRules($annonce, $equide);
            if (!empty($validationErrors)) {
                foreach ($validationErrors as $error) {
                    $this->addFlash('error', $error);
                }
                return $this->renderForm('annonce/new.html.twig', [
                    'annonce' => $annonce,
                    'equide' => $equide,
                    'annonceForm' => $annonceForm,
                ]);
            }

            try {
                // Utilisation d'une transaction pour garantir la cohérence
                $entityManager->beginTransaction();

                // 1. Enregistrer l'équidé
                $this->saveEquide($equide, $entityManager);

                // 2. Enregistrer l'annonce
                $this->saveAnnonce($annonce, $equide, $entityManager);

                // 3. Traiter et enregistrer les images
                $this->processImages($annonceForm, $annonce, $entityManager);

                // Valider la transaction
                $entityManager->commit();

                $this->addFlash('success', 'Votre annonce a été publiée avec succès !');
                return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);

            } catch (\Exception $e) {
                // Annuler la transaction en cas d'erreur
                $entityManager->rollback();
                $this->addFlash('error', 'Une erreur est survenue lors de la publication de l\'annonce');

                // Log l'erreur pour le débogage
                // $this->logger->error('Erreur lors de la création d\'annonce: ' . $e->getMessage());
            }
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'equide' => $equide,
            'annonceForm' => $annonceForm,
        ]);
    }

    /**
     * Valide les règles métier
     */
    private function validateBusinessRules(Annonce $annonce, Equide $equide): array
    {
        $errors = [];

        if ($annonce->getPrix() < 0) {
            $errors[] = "Le prix ne peut pas être négatif";
        }

        if ($equide->getTaille() < 0) {
            $errors[] = "La taille ne peut pas être négative";
        }

        // Ajouter d'autres validations si nécessaire
        if ($annonce->getPrix() > 10000000) {
            $errors[] = "Le prix ne peut pas dépasser 10 000 000 €";
        }

        return $errors;
    }

    /**
     * Enregistre l'équidé
     */
    private function saveEquide(Equide $equide, EntityManagerInterface $entityManager): void
    {
        $equide->setProprio($this->getUser());
        $entityManager->persist($equide);
        $entityManager->flush(); // Flush pour obtenir l'ID de l'équidé
    }

    /**
     * Enregistre l'annonce
     */
    private function saveAnnonce(Annonce $annonce, Equide $equide, EntityManagerInterface $entityManager): void
    {

        $annonce->setDatecreation(new \DateTime());
        $annonce->setActivation(true);
        $annonce->setUtilisateurAnnonce($this->getUser());
        $annonce->setEquide($equide);

        $entityManager->persist($annonce);
        $entityManager->flush();

    }

    /**
     * Traite et enregistre les images
     */
    private function processImages(
        FormInterface $annonceForm,
        Annonce $annonce,
        EntityManagerInterface $entityManager
    ): void {
        $images = $annonceForm->get('images')->getData();

        if (empty($images)) {
            return; // Pas d'images à traiter
        }

        foreach ($images as $uploadedFile) {
            try {
                $image = $this->createImageFromUpload($uploadedFile, $annonce);
                $entityManager->persist($image);
            } catch (\Exception $e) {
                // Log l'erreur et continuer avec les autres images
                // $this->logger->warning('Erreur lors du traitement d\'une image: ' . $e->getMessage());
                continue;
            }
        }

        $entityManager->flush(); // Enregistrer toutes les images en une fois
    }

    /**
     * Crée une entité Image à partir d'un fichier uploadé
     */
    private function createImageFromUpload(UploadedFile $uploadedFile, Annonce $annonce): Image
    {
        // Validation du fichier
        if (!$uploadedFile->isValid()) {
            throw new \Exception('Fichier invalide');
        }

        // Vérification du type MIME
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($uploadedFile->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('Type de fichier non autorisé');
        }

        // Vérification de la taille (5Mo max)
        if ($uploadedFile->getSize() > 5 * 1024 * 1024) {
            throw new \Exception('Fichier trop volumineux');
        }

        // Génération d'un nom unique et sécurisé
        $fileName = $this->generateSecureFileName($uploadedFile);

        // Déplacement du fichier
        $uploadedFile->move(
            $this->getParameter('images_directory'),
            $fileName
        );

        // Création de l'entité Image
        $image = new Image();
        $image->setLienImage('imgAnnonce/' . $fileName);
        $image->setAnnonceImage($annonce);

        return $image;
    }

    /**
     * Génère un nom de fichier sécurisé et unique
     */
    private function generateSecureFileName(UploadedFile $uploadedFile): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);

        return $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
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
    public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $annonce->getUtilisateurAnnonce() || $this->getUser() === null) {
            $this->addFlash('error', "Accès non autorisé");
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persister l'équidé si nécessaire
            if ($annonce->getEquide()) {
                $entityManager->persist($annonce->getEquide());
            }

            // 1. Suppression des anciennes images cochées
            $idsToDelete = $request->request->all('delete_images');
            foreach ($annonce->getImages() as $image) {
                if (in_array($image->getId(), $idsToDelete)) {
                    $annonce->removeImage($image);
                    $entityManager->remove($image);

                    // Supprimer le fichier physique
                    $filesystem = new Filesystem();
                    $filePath = $this->getParameter('images_directory') . '/' . basename($image->getLienimage());
                    if ($filesystem->exists($filePath)) {
                        $filesystem->remove($filePath);
                    }
                }
            }

            // 2. Ajout des nouvelles images
            $images = $form->get('images')->getData();

            if ($images && (is_array($images) || $images instanceof \Traversable)) {
                foreach ($images as $imageFile) {
                    if ($imageFile instanceof \Symfony\Component\HttpFoundation\File\UploadedFile &&
                        $imageFile->getError() === UPLOAD_ERR_OK) {

                        try {
                            // Vérifier la limite de 5 images
                            if ($annonce->getImages()->count() >= 5) {
                                $this->addFlash('warning', 'Limite de 5 images atteinte');
                                break;
                            }

                            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                            $imageFile->move($this->getParameter('images_directory'), $newFilename);

                            $image = new Image();
                            $image->setLienimage('imgAnnonce/' . $newFilename);
                            $image->setAnnonceImage($annonce);
                            $entityManager->persist($image);

                            $annonce->addImage($image);

                        } catch (\Exception $e) {
                            $this->addFlash('error', 'Erreur lors de l\'upload: ' . $e->getMessage());
                            continue;
                        }
                    }
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Annonce modifiée avec succès');
            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonceForm' => $form,
            'annonce' => $annonce,
        ]);
    }
    #[Route('annonce/delete/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $annonce->getUtilisateurAnnonce()) {
            $this->addFlash('error', 'Accès non autorisé');
            return $this->redirectToRoute('homepage');
        }

        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('homepage');
    }
}
