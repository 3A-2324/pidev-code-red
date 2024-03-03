<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez entrer le nom de l ingredient')]
    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    // #[ORM\Column]
    // private ?int $Calories = null;
    
    //#[Assert\NotBlank(message: 'Veuillez selectionner une image')]
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Recette::class, mappedBy: 'Ingredients')]
    private Collection $recettes;

    #[ORM\Column(length: 255)]
    private ?string $categorieing = null;

   // #[ORM\Column]
    //private ?int $caloriesing = null;



    public function __construct()
    {
        $this->recettes = new ArrayCollection();

    }


  

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

    // public function getCalories(): ?int
    // {
    //     return $this->Calories;
    // }

    // public function setCalories(int $Calories): static
    // {
    //     $this->Calories = $Calories;

    //     return $this;
    // }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function __toString():string{
        return $this->Nom;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->addIngredient($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            $recette->removeIngredient($this);
        }

        return $this;
    }

    // public function __toString(): string
    // {
    //     return $this->Nom; // Assuming "name" is the property you want to use as the string representation
    // }

    public function getCategorieing(): ?string
    {
        return $this->categorieing;
    }

    public function setCategorieing(string $categorieing): static
    {
        $this->categorieing = $categorieing;

        return $this;
    }

   // public function getCaloriesing(): ?int
    //{
   //     return $this->caloriesing;
   // }

    //public function setCaloriesing(int $caloriesing): static
    //{
    //    $this->caloriesing = $caloriesing;
//
    //    return $this;
    //}


}
