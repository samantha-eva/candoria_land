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

    /**
     * @var Collection<int, Bonbons>
     */
    #[ORM\OneToMany(targetEntity: Bonbons::class, mappedBy: 'id_categorie')]
    private Collection $bonbons;

    public function __construct()
    {
        $this->bonbons = new ArrayCollection();
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
            $bonbon->setIdCategorie($this);
        }

        return $this;
    }

    public function removeBonbon(Bonbons $bonbon): static
    {
        if ($this->bonbons->removeElement($bonbon)) {
            // set the owning side to null (unless already changed)
            if ($bonbon->getIdCategorie() === $this) {
                $bonbon->setIdCategorie(null);
            }
        }

        return $this;
    }
}
