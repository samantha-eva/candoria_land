<?php

namespace App\Tests\Entity;

use App\Entity\Bonbons;
use App\Entity\Marques;
use App\Entity\SousCategories;
use PHPUnit\Framework\TestCase;

class BonbonsTest extends TestCase
{
    public function testBonbonsEntity(): void
    {
        $bonbon = new Bonbons();

        // Test setter and getter for "nom"
        $bonbon->setNom('Caramel');
        $this->assertEquals('Caramel', $bonbon->getNom());

        // Test setter and getter for "image"
        $bonbon->setImage('caramel.jpg');
        $this->assertEquals('caramel.jpg', $bonbon->getImage());

        // Test setter and getter for "prix"
        $bonbon->setPrix('4.99');
        $this->assertEquals('4.99', $bonbon->getPrix());

        // Test setter and getter for "poids"
        $bonbon->setPoids('200g');
        $this->assertEquals('200g', $bonbon->getPoids());

        // Test setter and getter for "description"
        $bonbon->setDescription('Delicious caramel candies.');
        $this->assertEquals('Delicious caramel candies.', $bonbon->getDescription());
    }

    public function testMarqueRelation(): void
    {
        $marque = new Marques();
        $marque->setNom('ChocoBrand'); 

        $bonbon = new Bonbons();
        $bonbon->setMarque($marque);

        $this->assertSame($marque, $bonbon->getMarque());
    }

    public function testSousCategorieRelation(): void
    {
    
        $sousCategorie = new SousCategories();
        $sousCategorie->setNom('Bonbons au caramel'); 

        $bonbon = new Bonbons();
        $bonbon->setSousCategorie($sousCategorie);

        $this->assertSame($sousCategorie, $bonbon->getSousCategorie());
    }

   
}
