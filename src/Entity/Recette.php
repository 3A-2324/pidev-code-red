<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank(message: 'Veuillez entrer le nom de la recette')]
    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[Assert\NotBlank(message: 'Veuillez selectionner une categorie')]
    #[ORM\Column(length: 255)]
    private ?string $Categorie = null;

    //#[Assert\NotBlank(message: 'Veuillez selectionner une image')]
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Assert\NotBlank(message: 'Veuillez entrer la description,')]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotBlank(message: 'Veuillez entrer le nombre de calories')]
    #[Assert\GreaterThanOrEqual(
    value: 0,
    message: "La valeur des calories de la recette ne peut pas être négative."
)]
    #[ORM\Column]
    private ?int $calorie_recette = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recettes')]
    private Collection $Ingredients;

    #[ORM\ManyToMany(targetEntity: Journal::class, mappedBy: 'RecetteRef')]
    private Collection $journals;

    public function __construct()
    {
        $this->Ingredients = new ArrayCollection();
        $this->journals = new ArrayCollection();
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

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(string $Categorie): static
    {
        $this->Categorie = $Categorie;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCalorieRecette(): ?int
    {
        return $this->calorie_recette;
    }

    public function setCalorieRecette(int $calorie_recette): static
    {
        $this->calorie_recette = $calorie_recette;

        return $this;
    }

    public function __toString():string{
        return $this->Nom;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->Ingredients;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->Ingredients->contains($ingredient)) {
            $this->Ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
        $this->Ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return Collection<int, Journal>
     */
    public function getJournals(): Collection
    {
        return $this->journals;
    }

    public function addJournal(Journal $journal): static
    {
        if (!$this->journals->contains($journal)) {
            $this->journals->add($journal);
            $journal->addRecetteRef($this);
        }

        return $this;
    }

    public function removeJournal(Journal $journal): static
    {
        if ($this->journals->removeElement($journal)) {
            $journal->removeRecetteRef($this);
        }

        return $this;
    }

   

   
   
}
