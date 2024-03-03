<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"La date d'ajout ne doit pas être vide.")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date_ajout = null;

    #[Assert\NotBlank(message:"La quantité ne doit pas être vide.")]
    #[Assert\GreaterThan(value:0, message:"La quantité doit être supérieure à zéro.")]
    #[ORM\Column]
    #[Assert\NotBlank(message:"La quantité ne doit pas être vide.")]
    #[Assert\GreaterThan(value:0, message:"La quantité doit être supérieure à zéro.")]
    private ?int $quantite = null;

    #[ORM\OneToMany(mappedBy: 'panier', targetEntity: Produit::class)]
    private Collection $produits;

    #[Assert\NotBlank(message:"L'utilisateur ne doit pas être vide.")]
    #[ORM\ManyToOne(inversedBy: 'paniers')]
    private ?User $user = null;


    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->Date_ajout;
    }

    public function setDateAjout(\DateTimeInterface $Date_ajout): static
    {
        $this->Date_ajout = $Date_ajout;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setPanier($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getPanier() === $this) {
                $produit->setPanier(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->quantite;
    }
}
