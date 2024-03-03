<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit ne doit pas être vide.")]
    private ?string $Nom_produit = null;

    #[Assert\NotBlank(message: "La description ne doit pas être vide.")]
    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[Assert\NotBlank(message: "Le prix ne doit pas être vide.")]
    #[Assert\GreaterThan(value: 0, message: "Le prix doit être supérieur à zéro.")]
    #[ORM\Column(type: "float")]
    private ?float $Prix = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Assert\NotBlank(message: "Les commandes ne doivent pas être vides.")]
    #[ORM\ManyToMany(targetEntity: Commande::class, inversedBy: 'produits')]
    private Collection $commandes;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Panier $panier = null;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->Nom_produit;
    }

    public function setNomProduit(string $Nom_produit): static
    {
        $this->Nom_produit = $Nom_produit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): static
    {
        $this->Prix = $Prix;

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

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        $this->commandes->removeElement($commande);

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): static
    {
        $this->panier = $panier;

        return $this;
    }


    public function __toString(): string
    {
        return (string) $this->Description;
    }
}
