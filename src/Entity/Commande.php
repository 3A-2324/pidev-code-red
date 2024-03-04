<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'La date de la commande ne doit pas être vide.')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_cmd = null;

    #[ORM\Column(length: 255)]
    private ?string $etat_cmd = null;

    #[Assert\NotBlank(message: 'La quantité commandée ne doit pas être vide.')]
    #[Assert\GreaterThan(value: 0, message: 'La quantité commandée doit être supérieure à zéro.')]
    #[Assert\Type(type: 'numeric', message: 'La quantité commandée doit être un nombre.')]
    #[ORM\Column]
    private ?int $qte_commande = null;

    #[Assert\NotBlank(message: 'Le total ne doit pas être vide.')]
    #[Assert\GreaterThan(value: 0, message: 'Le total doit être supérieur à zéro.')]
    #[Assert\Type(type: 'numeric', message: 'Le total doit être un nombre.')]
    #[ORM\Column]
    private ?int $total = null;

    #[Assert\NotBlank(message: 'L\'utilisateur ne doit pas être vide.')]
    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    #[Assert\NotBlank(message: 'Les produits de la commande ne doivent pas être vides.')]
    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'commandes')]
    private Collection $produits;


    public function __toString(): string
    {
        return (string) $this->etat_cmd;
    }
    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCmd(): ?\DateTimeInterface
    {
        return $this->date_cmd;
    }

    public function setDateCmd(\DateTimeInterface $date_cmd): static
    {
        $this->date_cmd = $date_cmd;

        return $this;
    }

    public function getEtatCmd(): ?string
    {
        return $this->etat_cmd;
    }

    public function setEtatCmd(string $etat_cmd): static
    {
        $this->etat_cmd = $etat_cmd;

        return $this;
    }

    public function getQteCommande(): ?int
    {
        return $this->qte_commande;
    }

    public function setQteCommande(int $qte_commande): static
    {
        $this->qte_commande = $qte_commande;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

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
            $produit->addCommande($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeCommande($this);
        }

        return $this;
    }
}
