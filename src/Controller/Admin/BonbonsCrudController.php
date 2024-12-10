<?php

namespace App\Controller\Admin;

use App\Entity\Bonbons;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BonbonsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bonbons::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('nom'),
            ImageField::new('image')
            ->setBasePath('/uploads')
            ->setUploadDir('public/uploads'),
            NumberField::new('prix')->setNumDecimals(0), 
            TextField::new('poid'),
            TextEditorField::new('description'),
            AssociationField::new('categorie', 'Catégorie'), // Add this line for the category relationship
            AssociationField::new('marque', 'Marque')
        ];
    }

    
}
