<?php

namespace App\Controller;

use App\Repository\CommandesRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaiementController extends AbstractController
{
    #[Route('/paiement/{commande_id}', name: 'app_paiement')]
    public function index($commande_id, CommandesRepository $commandesRepository): Response
    {
        // Assurez-vous de définir votre clé API Stripe correctement
        Stripe::setApiKey($_ENV['STRIPE_API_KEY']); 

        // Votre domaine pour les liens d'images
        $YOUR_DOMAIN = 'https://127.0.0.1:8000';

        // Récupérer la commande via son ID
        $commande = $commandesRepository->find($commande_id);
        $products_stripe = [];

        // Construire les éléments de ligne (line items) pour Stripe
        foreach ($commande->getCommandeDetails() as $product) {
            $products_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => number_format($product->getProduit()->getPrix() * 100, 0, '', 2), // Convertir en centimes
                    'product_data' => [
                        'name' => $product->getProduit()->getNom(),
                        'images' => [$YOUR_DOMAIN . '/uploads/' . $product->getProduit()->getImage()],
                    ],
                ],
                'quantity' => $product->getQuantite(),
            ];
        }

        // Créer une session Stripe de paiement
        $checkout_session = Session::create([
            'line_items' => $products_stripe, // Passez les éléments de ligne directement ici
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        // Rediriger l'utilisateur vers la page de paiement Stripe
        return $this->redirect($checkout_session->url);
    }
}
