<?php

namespace App\Controller\Admin;

use App\Entity\Adresses;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AdressesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adresses::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('adresse'),
            TextField::new('code_postal'),
            AssociationField::new('user', 'Utilisateur') // Association avec l'entité Users
                ->setCrudController(UsersCrudController::class) // Optionnel : permet d'aller au CRUD utilisateur depuis ce champ
                ->setColumns('col-md-6') // Affichage en 50% de la largeur
        ];
    }
    
}
