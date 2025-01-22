<?php

namespace App\Service;

use App\Repository\BonbonsRepository;

class CartService
{
    private array $cart = [];
    private BonbonsRepository $bonbonsRepository;

    public function __construct(BonbonsRepository $bonbonsRepository)
    {
        $this->bonbonsRepository = $bonbonsRepository;
    }

    public function addProduct(int $productId, int $quantity): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId] += $quantity;
        } else {
            $this->cart[$productId] = $quantity;
        }
    }

    public function getTotalItems(): int
    {
        return array_sum($this->cart);
    }

    public function getTotalPrice(): float
    {
        $total = 0;
        foreach ($this->cart as $productId => $quantity) {
            $product = $this->bonbonsRepository->find($productId);
            if ($product) {
                $total += $product->getPrix() * $quantity;
            }
        }
        return $total;
    }

    public function getCart(): array
    {
        return $this->cart;
    }
}
