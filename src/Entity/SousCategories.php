<?php

namespace App\Entity;

use App\Repository\SousCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SousCategoriesRepository::class)]
class SousCategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Bonbons>
     */
    #[ORM\OneToMany(targetEntity: Bonbons::class, mappedBy: 'SousCategorie')]
    private Collection $bonbons;

    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'sousCategories')]
    private Collection $categories;

    public function __construct()
    {
        $this->bonbons = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Bonbons>
     */
    public function getBonbons(): Collection
    {
        return $this->bonbons;
    }

    public function addBonbon(Bonbons $bonbon): static
    {
        if (!$this->bonbons->contains($bonbon)) {
            $this->bonbons->add($bonbon);
            $bonbon->setSousCategorie($this);
        }

        return $this;
    }

    public function removeBonbon(Bonbons $bonbon): static
    {
        if ($this->bonbons->removeElement($bonbon)) {
            // set the owning side to null (unless already changed)
            if ($bonbon->getSousCategorie() === $this) {
                $bonbon->setSousCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

     // Ajouter cette méthode
     public function __toString(): string
     {
         return $this->nom ?? '';
     }

   
  
}
