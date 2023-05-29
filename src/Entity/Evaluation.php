<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Range(min: 1, max: 5, notInRangeMessage: 'Vous devez spécifier une note entre {{ min }} et {{ max }}.')]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?User $fk_user = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Recette $fk_recette = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getFkUser(): ?User
    {
        return $this->fk_user;
    }

    public function setFkUser(?User $fk_user): self
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    public function getFkRecette(): ?Recette
    {
        return $this->fk_recette;
    }

    public function setFkRecette(?Recette $fk_recette): self
    {
        $this->fk_recette = $fk_recette;

        return $this;
    }
}
