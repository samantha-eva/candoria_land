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
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

class BonbonsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bonbons::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $isPromotion = BooleanField::new('isPromotion', 'En promotion ?');
        $isNouveau = BooleanField::new('isNouveau', 'Nouveauté ?');
        $isOriginal = BooleanField::new('isOriginal', 'Original ?');


        $pourcentage = NumberField::new('pourcentage', 'Réduction (%)');

        return [
            //IdField::new('id'),
            TextField::new('nom'),
            ImageField::new('image')
            ->setBasePath('/uploads')
            ->setUploadDir('public/uploads')
            ->setRequired($pageName === Crud::PAGE_NEW),
            NumberField::new('prix', 'Prix')->setNumDecimals(2),
            TextField::new('poids'),
            TextEditorField::new('description'),
            AssociationField::new('categories', 'Catégorie')->setFormTypeOption('by_reference', false),
            AssociationField::new('marque', 'Marque'),
            $isPromotion,
            $pourcentage,
            $isNouveau,
            $isOriginal
        ];
    }

    
    public function configureAssets(Assets $assets): Assets
    {
        return $assets
           
            ->addJsFile('assets/js/admin/bonbon.js');
    }

    
}
