<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SendEmailService;

class ContactController extends AbstractController
{

    private $cartService;
    private SendEmailService $sendEmailService;

    public function __construct( CartService $cartService, SendEmailService $sendEmailService)
    {
        $this->cartService = $cartService;
        $this->sendEmailService = $sendEmailService;
    }


    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {

         // Récupérer les données du panier
         $totalItems = $this->cartService->getTotalItems($request);
         $totalPrice = $this->cartService->getTotalPrice($request);
        


         if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $subject = $request->request->get('subject');
            $message = $request->request->get('message');

            if ($email && $subject && $message) {
                // Envoi de l'email
                $this->sendEmailService->send(
                    'no-reply@example.com', 
                    $email, 
                    'Contact - candoria ', 
                    'contact',
                    compact('message')
                );

                $this->addFlash('success', 'Votre message a bien été envoyé.');
                return $this->redirectToRoute('app_contact');
            }

            $this->addFlash('error', 'Tous les champs sont obligatoires.');
        }
  
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'cart_total_items' => $totalItems,
            'cart_total_price' => $totalPrice,
        ]);
    }
    
}
