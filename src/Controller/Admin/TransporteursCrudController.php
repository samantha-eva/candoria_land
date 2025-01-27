<?php

namespace App\Controller\Admin;

use App\Entity\Transporteurs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class TransporteursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Transporteurs::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextEditorField::new('description'),
            NumberField::new('PrixTtc', 'Prix TTC')->setNumDecimals(2),
        ];
    }
    
}
