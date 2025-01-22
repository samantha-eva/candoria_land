<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\ManyToMany(targetEntity: Bonbons::class, mappedBy: 'categories')]
    private Collection $bonbons;

    public function __construct()
    {
        $this->bonbons = new ArrayCollection();
    }

    
     // Add the __toString() method
     public function __toString(): string
     {
         return $this->libelle ?? '';
     }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
  

      /**
     * @return Collection<int, SousCategories>
     */
    public function getBonbons(): Collection
    {
        return $this->bonbons;
    }

    public function addBonbons(Bonbons $bonbons): static
    {
        if (!$this->bonbons->contains($bonbons)) {
            $this->bonbons->add($bonbons);
            $bonbons->addCategories($this);
        }

        return $this;
    }

    public function removeBonbons(Bonbons $bonbons): static
    {
        if ($this->bonbons->removeElement($bonbons)) {
            $bonbons->removeCategories($this);
        }

        return $this;
    }

    
    
}
