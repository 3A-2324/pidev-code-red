<?php

namespace App\Entity;

use App\Repository\JournalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JournalRepository::class)]
class Journal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column]
    private ?int $calories_journal = null;

    #[ORM\ManyToMany(targetEntity: Recette::class, inversedBy: 'journals')]
    private Collection $RecetteRef;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\ManyToOne(inversedBy: 'journals')]
    private ?User $id_user = null;

    public function __construct()
    {
        $this->RecetteRef = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(?\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getCaloriesJournal(): ?int
{
    return $this->calories_journal;
}



   

    private function calculateTotalCalories(): int
    {
        $totalCalories = 0;

        // Loop through all the Recettes associated with this Journal and sum their calories
        foreach ($this->RecetteRef as $recette) {
            $totalCalories += $recette->getCalorieRecette();
        }

        // Update the calories_journal property
        $this->calories_journal = $totalCalories;

        return $totalCalories;
    }

    public function setCaloriesJournal(): static
    {
        $totalCalories = $this->calculateTotalCalories();
        
        $this->calories_journal = $totalCalories;
        
        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecetteRef(): Collection
    {
        return $this->RecetteRef;
    }

    public function addRecetteRef(Recette $recetteRef): static
    {
        if (!$this->RecetteRef->contains($recetteRef)) {
            $this->RecetteRef->add($recetteRef);
        }

        return $this;
    }

    public function removeRecetteRef(Recette $recetteRef): static
    {
        $this->RecetteRef->removeElement($recetteRef);

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }
}
