<?php

namespace App\Controller;

use App\Entity\CommandeDetails;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\CartService;
use App\Form\CommandeFormType;

class CartController extends AbstractController
{

    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function index(Request $request): Response
    {
        $totalPrice = $this->cartService->getTotalPrice($request);
        $cart = $this->cartService->getCartContents($request);
        $totalItems = $this->cartService->getTotalItems($request);
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
       
        $form = $this->createForm(CommandeFormType::class, null, [
            'user' => $user, // Passer l'utilisateur au formulaire
        ]);
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart_total_price' => $totalPrice,
            'cart' => $cart,
            'cart_total_items' => $totalItems,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request): JsonResponse
    {
        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);

        if (isset($data['id'], $data['quantity'])) {
            // Ajouter le produit au panier
            $this->cartService->addProduct((int)$data['id'], (int)$data['quantity']);
           
            // Retourner les informations mises à jour du panier
            return new JsonResponse([
                'totalItems' => $this->cartService->getTotalItems(),
                'totalPrice' => $this->cartService->getTotalPrice(),
                'cartContents' => $this->cartService->getCartContents(),
            ]);
        }

        return new JsonResponse(['error' => 'Invalid data'], 400);
    }
}
