<?php

namespace App\Entity;

use App\Entity\Recette;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Recette::class, inversedBy: 'categories')]
    private Collection $fk_recette;

    public function __construct()
    {
        $this->fk_recette = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getFkRecette(): Collection
    {
        return $this->fk_recette;
    }

    public function addFkRecette(Recette $fkRecette): self
    {
        if (!$this->fk_recette->contains($fkRecette)) {
            $this->fk_recette->add($fkRecette);
            $fkRecette->addCategory($this);
        }

        return $this;
    }

    public function removeFkRecette(Recette $fkRecette): self
    {
        $this->fk_recette->removeElement($fkRecette);

        return $this;
    }
}
