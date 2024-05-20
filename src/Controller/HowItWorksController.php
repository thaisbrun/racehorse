<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Ce controller est lié à la page "comment ça marche", expliquant les fonctionnalités principales du site.

class HowItWorksController extends AbstractController
{
    #[Route('/howItWorks', name: 'howItWorks')]
    public function index(): Response
    {
        return $this->render('how_it_works/index.html.twig', [
            'controller_name' => 'HowItWorksController',
        ]);
    }
}
