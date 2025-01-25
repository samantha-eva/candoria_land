<?php

namespace App\Controller\Admin;

use App\Entity\Bonbons;
use App\Entity\Categories;
use App\Entity\Users;
use App\Entity\Marques;
use App\Entity\Adresses;
use App\Entity\Transporteurs;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(BonbonsCrudController::class)->generateUrl());

        
        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Candoria Land');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
         yield MenuItem::linkToCrud('Bonbons', 'fas fa-list', Bonbons::class);
         yield MenuItem::linkToCrud('Categories', 'fas fa-list', Categories::class);
         yield MenuItem::linkToCrud('Users', 'fas fa-list', Users::class);
         yield MenuItem::linkToCrud('Marques', 'fas fa-list', Marques::class);
         yield MenuItem::linkToCrud('Adresses', 'fas fa-list', Adresses::class);
         yield MenuItem::linkToCrud('Transporteurs', 'fas fa-list', Transporteurs::class);
    }
}
