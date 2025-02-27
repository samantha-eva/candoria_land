<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'Il y a déjà un compte pour cet utilisateur')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(length: 180)]
    private ?string $nom = null;
    
    #[ORM\Column(length: 180)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $telephone = null;


    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];  // Valeur par défaut

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    // Champs non persistants
    private ?string $oldPassword = null;
    private ?string $newPassword = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isVerified = false;

    /**
     * @var Collection<int, Adresses>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Adresses::class, cascade: ['persist', 'remove'])]
    private Collection $adresses;

    /**
     * @var Collection<int, Commandes>
     */
    #[ORM\OneToMany(targetEntity: Commandes::class, mappedBy: 'user')]
    private Collection $commandes;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this; 
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(?string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): self
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Retourne le rôle principal à afficher dans le listing.
     */
    public function getMainRole(): string
    {
        $rolePriority = [
            'ROLE_ADMIN' => 'Admin',
            'ROLE_USER' => 'Utilisateur',
        ];

        foreach (array_keys($rolePriority) as $role) {
            if (in_array($role, $this->roles, true)) {
                return $rolePriority[$role];
            }
        }

        return 'Aucun rôle'; // Rôle par défaut si aucun rôle connu n'est trouvé
    }


     // Add the __toString() method
     public function __toString(): string
     {
         return $this->nom. ' '.$this->prenom ?? '';
     }

     /**
      * @return Collection<int, Adresses>
      */
     public function getAdresses(): Collection
     {
         return $this->adresses;
     }

     public function addAdress(Adresses $adress): static
     {
         if (!$this->adresses->contains($adress)) {
             $this->adresses->add($adress);
             $adress->setUser($this);
         }

         return $this;
     }

     public function removeAdress(Adresses $adress): static
     {
         if ($this->adresses->removeElement($adress)) {
             // set the owning side to null (unless already changed)
             if ($adress->getUser() === $this) {
                 $adress->setUser(null);
             }
         }

         return $this;
     }

     /**
      * @return Collection<int, Commandes>
      */
     public function getCommandes(): Collection
     {
         return $this->commandes;
     }

     public function addCommande(Commandes $commande): static
     {
         if (!$this->commandes->contains($commande)) {
             $this->commandes->add($commande);
             $commande->setUser($this);
         }

         return $this;
     }

     public function removeCommande(Commandes $commande): static
     {
         if ($this->commandes->removeElement($commande)) {
             // set the owning side to null (unless already changed)
             if ($commande->getUser() === $this) {
                 $commande->setUser(null);
             }
         }

         return $this;
     }
}
