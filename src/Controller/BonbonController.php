<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BonbonsRepository;
use App\Repository\MarquesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\SousCategoriesRepository;
use Symfony\Component\HttpFoundation\Request;

class BonbonController extends AbstractController

{

    private $bonbonsRepository;
    private $marquesRepository;
    private $categoriesRepository;

    public function __construct(BonbonsRepository $bonbonsRepository,
     MarquesRepository $marquesRepository,
     CategoriesRepository $categoriesRepository,
    )
    
    {
        $this->bonbonsRepository = $bonbonsRepository;
        $this->marquesRepository = $marquesRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    #[Route('/boutique', name: 'app_shop')]
    public function index(Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');

        // Décoder le tableau JSON des catégories
        $categoriesJson = $request->query->get('categories', '[]');
        $selectedCategories = json_decode($categoriesJson, true) ?? [];

        $marquesJson = $request->query->get('marques', '[]');
        $selectedMarques = json_decode($marquesJson, true) ?? []; // Cela renvoie un tableau des IDs des marques sélectionnées
      
        // Récupération des bonbons
        $bonbons = $this->bonbonsRepository->findBySearchTermAndCategoriesAndMarques($searchTerm, $selectedCategories, $selectedMarques);

        // Récupération des autres données
        $marques = $this->marquesRepository->findAll();
        $categories = $this->categoriesRepository->findAllCategories();

        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BonbonController',
            'bonbons' => $bonbons,
            'marques' => $marques,
            'categories' => $categories,
        ]);
    }

}
