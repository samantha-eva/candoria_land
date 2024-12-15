<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'app_profile')]
    public function edit( Users $user, Request $request, EntityManagerInterface $manager): Response
    {

        if(!$this->getUser()) { //check si le user est connecté
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() !== $user){
            return $this->redirectToRoute('app_home');
        }

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
        ]);
    }
}
