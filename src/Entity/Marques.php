<?php

namespace App\Entity;

use App\Repository\MarquesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarquesRepository::class)]
class Marques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;


    #[ORM\OneToMany(mappedBy: 'marque', targetEntity: Bonbons::class, orphanRemoval: true)]
    private Collection $bonbons;

    public function __construct()
    {
        $this->bonbons = new ArrayCollection();
    }

     // Add the __toString() method
     public function __toString(): string
     {
         return $this->nom ?? '';
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

    public function getBonbons(): Collection
    {
        return $this->bonbons;
    }

    public function addBonbon(Bonbons $bonbon): static
    {
        if (!$this->bonbons->contains($bonbon)) {
            $this->bonbons->add($bonbon);
            $bonbon->setMarque($this);
        }

        return $this;
    }

    public function removeBonbon(Bonbons $bonbon): static
    {
        if ($this->bonbons->removeElement($bonbon)) {
            // Set the owning side to null (unless already changed)
            if ($bonbon->getMarque() === $this) {
                $bonbon->setMarque(null);
            }
        }

        return $this;
    }
}
