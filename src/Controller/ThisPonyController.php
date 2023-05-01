<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Equide;
use App\Repository\MyClassRepository;
use App\Repository\EquideRepository;
use PhpParser\Node\Expr\Cast\Int_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThisPonyController extends AbstractController
{
    #[Route('/this_pony/{idequidea}', name: 'this_pony', methods: 'GET')]
    public function index(int $idequidea, MyClassRepository $annonceRepo, EquideRepository $equideRepo): Response
    {
        $annonce = $annonceRepo->find($idequidea);
        $equide = $equideRepo->find($idequidea);

        return $this->render('this_pony/index.html.twig', [
        'controller_name' => 'ThisPonyController',
            'annonce' => $annonce,
            'equide' => $equide,

        ]);
    }
}
