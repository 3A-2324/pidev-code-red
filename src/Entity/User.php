<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    private ?string $Prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
private ?\DateTimeInterface $Date_de_naissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'choisissez votre genre')]
    private ?string $Genre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    private ?string $Adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
     #[Assert\Regex(
         pattern:"/^\d{8}$/",
        message:"Le contenu doit être composé exactement de 8 chiffres.")]
    private ?string $Num_de_telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'choisissez votre Role')]
    private ?string $Role = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'L email ne peut pas etre vide')]
    
    #[Assert\Regex(
             pattern:"/^[^@]+@[^@]+\.[^@]+$/",
             message:"L'email n'est pas valide Le format attendu est 'user@example.com"
        )
        ]    private ?string $Email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
  
    #[Assert\Regex(
           pattern:"/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/",
           message:"Le mot de passe doit comporter au moins 8 caractères avec au moins une majuscule, une minuscule, un chiffre et un symbole."
          
        
        
    )]
    private ?string $Mot_de_passe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getDateDeNaissance(): \DateTimeInterface
    {
        return $this->Date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $Date_de_naissance): static
    {
        $this->Date_de_naissance = $Date_de_naissance;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->Genre;
    }

    public function setGenre(string $Genre): static
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getNumDeTelephone(): ?string
    {
        return $this->Num_de_telephone;
    }

    public function setNumDeTelephone(string $Num_de_telephone): static
    {
        $this->Num_de_telephone = $Num_de_telephone;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(string $Role): static
    {
        $this->Role = $Role;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->Mot_de_passe;
    }

    public function setMotDePasse(string $Mot_de_passe): static
    {
        $this->Mot_de_passe = $Mot_de_passe;

        return $this;
    }
}
