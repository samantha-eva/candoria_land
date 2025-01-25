<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BonbonsRepository;
use App\Repository\MarquesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\CartService;

class BonbonController extends AbstractController

{

    private $bonbonsRepository;
    private $marquesRepository;
    private $categoriesRepository;
    private $cartService;


    public function __construct(BonbonsRepository $bonbonsRepository,
     MarquesRepository $marquesRepository,
     CategoriesRepository $categoriesRepository,
     CartService $cartService,
    )
    
    {
        $this->bonbonsRepository = $bonbonsRepository;
        $this->marquesRepository = $marquesRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->cartService = $cartService;
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

         // Récupérer les données du panier
        $totalItems = $this->cartService->getTotalItems($request);
        $totalPrice = $this->cartService->getTotalPrice($request);


        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BonbonController',
            'bonbons' => $bonbons,
            'marques' => $marques,
            'categories' => $categories,
            'cart_total_items' => $totalItems,
            'cart_total_price' => $totalPrice,
        ]);
    }

    #[Route('/boutique/{id}', name: 'app_detail')]
    public function show(int $id, Request $request): Response
    {
        $bonbon = $this->bonbonsRepository->findBonbonById($id);
        // Récupérer les données du panier
        $totalItems = $this->cartService->getTotalItems($request);
        $totalPrice = $this->cartService->getTotalPrice($request);
    
        return $this->render('boutique/details_bonbon.html.twig', [
            'controller_name' => 'BonbonController',
            'bonbon' => $bonbon,
            'cart_total_items' => $totalItems,
            'cart_total_price' => $totalPrice,
        ]);
    }

    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request): JsonResponse
    {
        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);
       

        if (isset($data['id'], $data['quantity'])) {
            // Ajouter le produit au panier en utilisant la session
            $this->cartService->addProduct($request, (int)$data['id'], (int)$data['quantity']);

            // Retourner les informations mises à jour du panier
            return new JsonResponse([
                'totalItems' => $this->cartService->getTotalItems($request),
                'totalPrice' => $this->cartService->getTotalPrice($request),
                'cartContents' => $this->cartService->getCartContents($request),
            ]);
        }

        return new JsonResponse(['error' => 'Invalid data'], 400);
    }

}
