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

    /**
     * Récupère la session depuis la requête.
     */
    private function getSession(Request $request)
    {
        return $request->getSession();
    }

    /**
     * Ajoute un produit au panier ou met à jour sa quantité.
     */
    public function addProduct(Request $request, int $productId, int $quantity): void
    {
        $cart = $this->getSession($request)->get(self::CART_KEY, []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $this->getSession($request)->set(self::CART_KEY, $cart);
    }

    /**
     * Supprime un produit du panier.
     */
    public function removeProduct(Request $request, int $productId): void
    {
        $cart = $this->getSession($request)->get(self::CART_KEY, []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $this->getSession($request)->set(self::CART_KEY, $cart);
    }

    /**
     * Vide complètement le panier.
     */
    public function clearCart(Request $request): void
    {
        $this->getSession($request)->remove(self::CART_KEY);
    }

    /**
     * Retourne le nombre total d'articles dans le panier.
     */
    public function getTotalItems(Request $request): int
    {
        $cart = $this->getSession($request)->get(self::CART_KEY, []);
        return array_sum($cart);
    }

    /**
     * Retourne le prix total de tous les produits dans le panier.
     */
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

    /**
     * Retourne le contenu détaillé du panier (produits et leurs informations).
     */
    public function getCartContents(Request $request): array
    {
        $cart = $this->getSession($request)->get(self::CART_KEY, []);
        $cartDetails = [];

        foreach ($cart as $productId => $quantity) {
            $product = $this->bonbonsRepository->find($productId);
            if ($product) {
                $cartDetails[] = [
                    'id' => $productId,
                    'name' => $product->getNom(),
                    'quantity' => $quantity,
                    'price' => $product->getPrix(),
                    'total' => $product->getPrix() * $quantity,
                ];
            }
        }

        return $cartDetails;
    }
}
