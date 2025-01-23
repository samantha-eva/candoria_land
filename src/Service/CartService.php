<?php

namespace App\Service;

use App\Repository\BonbonsRepository;
use Symfony\Component\HttpFoundation\Request;

class CartService
{
    private const CART_KEY = 'cart';
    private BonbonsRepository $bonbonsRepository;

    public function __construct(BonbonsRepository $bonbonsRepository)
    {
        $this->bonbonsRepository = $bonbonsRepository;
    }

    private function getSession(Request $request)
    {
        return $request->getSession();
    }

    public function addProduct(Request $request, int $productId, int $quantity): void
    {
        // Récupérer le panier depuis la session
        $cart = $this->getSession($request)->get(self::CART_KEY, []);

        // Ajouter ou mettre à jour la quantité du produit
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        // Sauvegarder le panier dans la session
        $this->getSession($request)->set(self::CART_KEY, $cart);
    }

    public function getTotalItems(Request $request): int
    {
        $cart = $this->getSession($request)->get(self::CART_KEY, []);

        return array_sum($cart);
    }

    public function getTotalPrice(Request $request): float
    {
        $cart = $this->getSession($request)->get(self::CART_KEY, []);
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $this->bonbonsRepository->find($productId);
            if ($product) {
                $total += $product->getPrix() * $quantity;
            }
        }

        return $total;
    }

}
