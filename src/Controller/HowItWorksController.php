<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
