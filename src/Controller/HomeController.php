<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BonbonsRepository;

class HomeController extends AbstractController
{

    private $cartService;

    public function __construct( CartService $cartService)
    {
        $this->cartService = $cartService;
    }


    #[Route('/', name: 'app_home')]
    public function index(Request $request , BonbonsRepository $bonbonsRepository): Response
    {
        $bonbons = $bonbonsRepository->findBonbonIsOriginal();

         // Récupérer les données du panier
         $totalItems = $this->cartService->getTotalItems($request);
         $totalPrice = $this->cartService->getTotalPrice($request);
  
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'cart_total_items' => $totalItems,
            'cart_total_price' => $totalPrice,
            'bonbons_original' =>$bonbons
        ]);
    }
}
