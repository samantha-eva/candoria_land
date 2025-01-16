<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BonbonsRepository;
class BonbonController extends AbstractController

{

    private $bonbonsRepository;

    public function __construct(BonbonsRepository $bonbonsRepository)
    {
        $this->bonbonsRepository = $bonbonsRepository;
    }

    #[Route('/boutique', name: 'app_shop')]
    public function index(): Response
    {

        $bonbons = $this->bonbonsRepository->findAll();

        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BonbonController',
            'bonbons'=> $bonbons
        ]);
    }
}
