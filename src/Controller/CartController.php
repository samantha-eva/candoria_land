<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\CartService;


class CartController extends AbstractController
{

    private CartService $cartService;
    
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
       
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

    #[Route('/cart/remove/{id}', name: 'cart_remove', methods: ['POST'])]
    public function removeItem(int $id, Request $request, CartService $cartService): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $cartService->removeProduct($id);

            return new JsonResponse(['status' => 'success'], 200);
        }

        return new JsonResponse(['status' => 'error'], 400);
    }

    
    #[Route('/cart/update/{id}', name:'cart_update', methods: ['POST'])]
    public function updateQuantity(Request $request, int $id, CartService $cartService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
       
        if (!isset($data['quantity']) || $data['quantity'] < 1) {
            return new JsonResponse(['message' => 'Quantité invalide'], Response::HTTP_BAD_REQUEST);
        }

        $quantity = (int) $data['quantity'];

        $cartService->updateProductQuantity($id, $quantity);

        return new JsonResponse(['message' => 'Quantité mise à jour']);
    }

}
