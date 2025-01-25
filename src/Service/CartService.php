<?php

namespace App\Service;

use App\Repository\BonbonsRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private const CART_KEY = 'cart';
    private BonbonsRepository $bonbonsRepository;
    private RequestStack $requestStack;

    public function __construct(BonbonsRepository $bonbonsRepository, RequestStack $requestStack)
    {
        $this->bonbonsRepository = $bonbonsRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * Récupère la session actuelle.
     */
    private function getSession()
    {
        $session = $this->requestStack->getSession();

        if (!$session) {
            throw new \RuntimeException('La session n\'est pas disponible.');
        }

        return $session;
    }

    /**
     * Ajoute un produit au panier ou met à jour sa quantité.
     */
    public function addProduct(int $productId, int $quantity): void
    {
        $cart = $this->getSession()->get(self::CART_KEY, []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $this->getSession()->set(self::CART_KEY, $cart);
    }

    /**
     * Supprime un produit du panier.
     */
    public function removeProduct(int $productId): void
    {
        $cart = $this->getSession()->get(self::CART_KEY, []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $this->getSession()->set(self::CART_KEY, $cart);
    }

    /**
     * Vide complètement le panier.
     */
    public function clearCart(): void
    {
        $this->getSession()->remove(self::CART_KEY);
    }

    /**
     * Retourne le nombre total d'articles dans le panier.
     */
    public function getTotalItems(): int
    {
        $cart = $this->getSession()->get(self::CART_KEY, []);
        return array_sum($cart);
    }

    /**
     * Retourne le prix total de tous les produits dans le panier.
     */
    public function getTotalPrice(): float
    {
        $cart = $this->getSession()->get(self::CART_KEY, []);
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
    public function getCartContents(): array
    {
        $cart = $this->getSession()->get(self::CART_KEY, []);
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
