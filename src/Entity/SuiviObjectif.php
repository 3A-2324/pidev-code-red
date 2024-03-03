<?php

namespace App\Entity;

use App\Repository\SuiviObjectifRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SuiviObjectifRepository::class)]
class SuiviObjectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Objectif $objectif = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual("today", message: 'La date de début doit être dans le futur ou pr.')]
    private ?\DateTimeInterface $date_suivi = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'Le contenu doit contenir uniquement des chiffres'
    )]
    private ?int $nouveau_poids = null;

    #[ORM\Column(length: 1000)]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdObjectif(): ?Objectif
    {
        return $this->objectif;
    }

    public function setIdObjectif(?Objectif $objectif): static
    {
        $this->objectif = $objectif;

        return $this;
    }

    public function getDateSuivi(): ?\DateTimeInterface
    {
        return $this->date_suivi;
    }

    public function setDateSuivi(\DateTimeInterface $date_suivi): static
    {
        $this->date_suivi = $date_suivi;

        return $this;
    }

    public function getNouveauPoids(): ?int
    {
        return $this->nouveau_poids;
    }

    public function setNouveauPoids(int $nouveau_poids): static
    {
        $this->nouveau_poids = $nouveau_poids;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
