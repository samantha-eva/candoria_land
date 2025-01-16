<?php

namespace App\Controller\Admin;

use App\Entity\SousCategories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SousCategoriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SousCategories::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // return [
        //     TextField::new('nom'),
         
        // ];
        return [
            TextField::new('nom'),
            AssociationField::new('categories') // Ajoute un champ pour sélectionner plusieurs catégories
                ->setFormTypeOption('by_reference', false), // Important pour les relations ManyToMany
        ];
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
