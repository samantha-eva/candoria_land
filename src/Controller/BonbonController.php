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
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\CartService;

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

    #[Route('/boutique/{id}', name: 'app_detail')]
    public function show(int $id): Response
    {
        $bonbon = $this->bonbonsRepository->findBonbonById($id);
        
        return $this->render('boutique/details_bonbon.html.twig', [
            'controller_name' => 'BonbonController',
            'bonbon' => $bonbon,
        ]);
    }


    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, CartService $cartService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['id']) || !isset($data['quantity'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $productId = $data['id'];
        $quantity = $data['quantity'];

        // Ajouter le produit au panier via un service
        $cartService->addProduct($productId, $quantity);

        // Retourner les nouvelles informations pour la barre de navigation
        return new JsonResponse([
            'totalItems' => $cartService->getTotalItems(),
            'totalPrice' => $cartService->getTotalPrice(),
        ]);
    }

}
