<?php

namespace App\Controller\Admin;

use App\Entity\Adresses;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdressesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adresses::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'users')->setFormTypeOption('by_reference', true),
            TextField::new('rue'),
            TextField::new('ville'),
            TextField::new('code_postal'),
            TextField::new('pays'),

        ];
    }
    
}
