<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Utilisateur;
use App\Entity\Image;
use App\Repository\MyClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(MyClassRepository $annonceRepo): Response
    {

        $annoncesVente = $annonceRepo->findByTypeAnnonce(1);
        $annoncesLocation = $annonceRepo->findByTypeAnnonce(2);
        $annoncesDP = $annonceRepo->findByTypeAnnonce(3);
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'annoncesVente' => $annoncesVente,
            'annoncesLocation' => $annoncesLocation,
            'annoncesDP' => $annoncesDP
        ]);
    }
}
