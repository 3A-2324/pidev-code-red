<?php

namespace App\Entity;

use App\Repository\ObjectifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ObjectifRepository::class)]
class Objectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    private ?string $sexe = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    private ?int $age = null;


    #[ORM\Column]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'Le contenu doit contenir uniquement des chiffres'
    )]
    
    private ?int $height = null;
    

    #[ORM\Column]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'Le contenu doit contenir uniquement des chiffres'
    )]
    private ?int $weight = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    private ?string $activityLevel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le contenu ne peut pas etre vide')]
    private ?string $choix = null;
     

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datee = null;


    #[ORM\Column]
    private ?int $calorie = null;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getActivityLevel(): ?string
    {
        return $this->activityLevel;
    }

    public function setActivityLevel(string $activityLevel): static
    {
        $this->activityLevel = $activityLevel;

        return $this;
    }

    public function getChoix(): ?string
    {
        return $this->choix;
    }

    public function setChoix(string $choix): static
    {
        $this->choix = $choix;

        return $this;
    }

    public function getDatee(): ?\DateTimeInterface
    {
        return $this->datee;
    }

    public function setDatee(\DateTimeInterface $datee): static
    {
        $this->datee = $datee;

        return $this;
    }

    public function getCalorie(): ?int
    {
        return $this->calorie;
    }

    public function setCalorie(int $calorie): static
    {
        $this->calorie = $calorie;

        return $this;
    }

   
}
