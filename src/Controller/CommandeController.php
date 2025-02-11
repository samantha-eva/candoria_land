<?php

namespace App\Controller;

use App\Entity\Bonbons;
use App\Entity\Commandes;
use App\Entity\CommandeDetails;
use App\Service\CartService;
use App\Form\CommandeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    private CartService $cartService;
    
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
       
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $totalPrice = $this->cartService->getTotalPrice($request);
        $cart = $this->cartService->getCartContents($request);
        $totalItems = $this->cartService->getTotalItems($request);
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
       
        $form = $this->createForm(CommandeFormType::class, null, [
            'user' => $user, // Passer l'utilisateur au formulaire
        ]);

        // Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Créer une nouvelle commande
            $commande = new Commandes();
            $commande->setUser($user);
            $commande->setTransporteur($form->get('transporteurs')->getData());
            $commande->setStatut('à payer');
            $commande->setPrixTotal($totalPrice);
            $commande->setCreatedAt(new \DateTime());
            $commande->setUpdatedAt(new \DateTime());

            // Sauvegarder la commande en base de données
            $entityManager->persist($commande);
            $entityManager->flush();
            
            foreach( $cart as $produit){
                $bonbon = $entityManager->getRepository(Bonbons::class)->find($produit['id']);

               
                $commande_detail  = new CommandeDetails();
                $commande_detail->setCommande($commande);
                $commande_detail->setProduit($bonbon);
                $commande_detail->setQuantite($produit['quantity']);
                $commande_detail->setPrix($produit['total']);

                $entityManager->persist($commande_detail);
            }

            $entityManager->flush();

            // Rediriger vers la page de confirmation ou de paiement
            return $this->redirectToRoute('app_paiement', [
                'commande_id' => $commande->getId(), // Passer l'ID de la commande en paramètre
            ]);
        }


        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart_total_price' => $totalPrice,
            'cart' => $cart,
            'cart_total_items' => $totalItems,
            'form' => $form->createView(),
        ]);
    }



}