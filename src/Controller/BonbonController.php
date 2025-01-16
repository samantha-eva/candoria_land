<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BonbonsRepository;
use App\Repository\MarquesRepository;
class BonbonController extends AbstractController

{

    private $bonbonsRepository;
    private $marquesRepository;

    public function __construct(BonbonsRepository $bonbonsRepository,MarquesRepository $marquesRepository)
    {
        $this->bonbonsRepository = $bonbonsRepository;
        $this->marquesRepository = $marquesRepository;
    }

    #[Route('/boutique', name: 'app_shop')]
    public function index(): Response
    {

        $bonbons = $this->bonbonsRepository->findAll();
        $marques= $this->marquesRepository->findAll();

        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BonbonController',
            'bonbons'=> $bonbons,
            'marques'=> $marques,
        ]);
    }
}
