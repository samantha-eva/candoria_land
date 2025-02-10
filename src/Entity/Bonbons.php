<?php

namespace App\Entity;

use App\Repository\BonbonsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: BonbonsRepository::class)]
class Bonbons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $poids = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'bonbons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Marques $marque = null;

    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'bonbons')]
    #[ORM\JoinTable(name: 'bonbon_categorie')]
    private Collection $categories;

    /**
     * @var Collection<int, CommandeDetails>
     */
    #[ORM\OneToMany(targetEntity: CommandeDetails::class, mappedBy: 'produit')]
    private Collection $commandeDetails;

    #[ORM\Column(type: 'boolean')]
    private bool $isPromotion = false;

    
    #[ORM\Column(type: 'boolean')]
    private bool $isNouveau = false;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $pourcentage = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->commandeDetails = new ArrayCollection();
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

    
    public function getImage(): ?string
    {
        return $this->image;
    }


    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }


    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(string $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMarque(): ?Marques
    {
        return $this->marque;
    }

    public function setMarque(?Marques $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function isPromotion(): bool
    {
        return $this->isPromotion;
    }

    public function setIsPromotion(bool $isPromotion): static
    {
        $this->isPromotion = $isPromotion;
        return $this;
    }

    public function isNouveau(): bool
    {
        return $this->isNouveau;
    }

    public function setIsNouveau(bool $isNouveau): static
    {
        $this->isNouveau = $isNouveau;
        return $this;
    }

    public function getPourcentage(): ?string
    {
        return $this->pourcentage;
    }

    public function setPourcentage(?string $pourcentage): static
    {
        $this->pourcentage = $pourcentage;
        return $this;
    }
    
    /**
    * @return Collection<int, Categories>
    */

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function addCategories(Categories $categories): self
    {
        if (!$this->categories->contains($categories)) {
            $this->categories->add($categories);
            $categories->addBonbons($this);
        }

        return $this;
    }

    public function removeCategories(Categories $categories): self
    {
        if ($this->categories->removeElement($categories)) {
            $categories->removeBonbons($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeDetails>
     */
    public function getCommandeDetails(): Collection
    {
        return $this->commandeDetails;
    }

    public function addCommandeDetail(CommandeDetails $commandeDetail): static
    {
        if (!$this->commandeDetails->contains($commandeDetail)) {
            $this->commandeDetails->add($commandeDetail);
            $commandeDetail->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeDetail(CommandeDetails $commandeDetail): static
    {
        if ($this->commandeDetails->removeElement($commandeDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandeDetail->getProduit() === $this) {
                $commandeDetail->setProduit(null);
            }
        }

        return $this;
    }
}
