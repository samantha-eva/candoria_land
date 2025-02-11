<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UsersRepository;
use App\Service\CartService;

class UserController extends AbstractController
{

    private $cartService;

    public function __construct( CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/user/edit/{id}', name: 'app_profile')]
    public function edit( Users $user, Request $request, EntityManagerInterface $manager): Response
    {

        if(!$this->getUser()) { //check si le user est connecté
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() !== $user){
            return $this->redirectToRoute('app_home');
        }

        $totalPrice = $this->cartService->getTotalPrice($request);
        $totalItems = $this->cartService->getTotalItems($request);

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien ete modifiées'
            );
            return $this->redirectToRoute('app_home');
        }


        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'cart_total_items' => $totalItems,
            'cart_total_price' => $totalPrice,
        ]);
    }

    #[Route('/user/commande/{id}', name: 'app_historique')]
    public function user_commande( Users $user, Request $request, UsersRepository $usersRepository): Response
    {
      

        if(!$this->getUser()) { //check si le user est connecté
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() !== $user){
            return $this->redirectToRoute('app_home');
        }
    
    
        $user = $usersRepository->findUserWithCommandes($user->getId());
        $commandes= $user->getCommandes()->toArray();

        $totalPrice = $this->cartService->getTotalPrice($request);
        $totalItems = $this->cartService->getTotalItems($request);

        return $this->render('user/historique_commande.html.twig', [
            'commandes' => $commandes,
            'cart_total_items' => $totalItems,
            'cart_total_price' => $totalPrice,
        ]);
    }

    #[Route('/user/detail_commande/{id}', name: 'app_detail_commande')]
    public function detail_commande(Users $user, UsersRepository $usersRepository, Request $request): Response
    {
        $totalPrice = $this->cartService->getTotalPrice($request);
        $totalItems = $this->cartService->getTotalItems($request);
        $user = $usersRepository->findUserWithCommandesAndDetail($user->getId());
        $orders= $user->getCommandes()->toArray();

        return $this->render('user/detail_commande.html.twig', [
            'orders' => $orders,
            'cart_total_items' => $totalItems,
            'cart_total_price' => $totalPrice,
        ]);

    }

}
