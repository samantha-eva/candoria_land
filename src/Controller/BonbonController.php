<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BonbonsRepository;
use App\Repository\MarquesRepository;
use App\Repository\CategoriesRepository;

class BonbonController extends AbstractController

{

    private $bonbonsRepository;
    private $marquesRepository;
    private $categoriesRepository;

    public function __construct(BonbonsRepository $bonbonsRepository,MarquesRepository $marquesRepository,CategoriesRepository $categoriesRepository)
    {
        $this->bonbonsRepository = $bonbonsRepository;
        $this->marquesRepository = $marquesRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    #[Route('/boutique', name: 'app_shop')]
    public function index(): Response
    {

        $bonbons    = $this->bonbonsRepository->findAll();
        $marques    = $this->marquesRepository->findAll();
        $categories = $this->categoriesRepository->findAll();

         return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BonbonController',
            'bonbons'=> $bonbons,
            'marques'=> $marques,
            'categories' => $categories
         ]);
     }
}
