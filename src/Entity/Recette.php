<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\Evaluation;
use App\Entity\Ingredients;
use App\Entity\ListedeCourses;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $preparationTime = null;

    #[ORM\Column]
    private ?int $cookingTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberPortions = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $instruction = null;


    #[ORM\OneToMany(mappedBy: 'fk_recette', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'fk_recette', targetEntity: Evaluation::class)]
    private Collection $evaluations;


    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'fk_recette')]
    private Collection $categories;


    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'recette')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: ListedeCourses::class, mappedBy: 'Liste')]
    private Collection $listedeCourses;


    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favorite_recettes')]

    private Collection $usersFavorite;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ingredient_text = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->listedeCourses = new ArrayCollection();
        $this->usersFavorite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(int $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getCookingTime(): ?int
    {
        return $this->cookingTime;
    }

    public function setCookingTime(int $cookingTime): self
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function getNumberPortions(): ?int
    {
        return $this->numberPortions;
    }

    public function setNumberPortions(?int $numberPortions): self
    {
        $this->numberPortions = $numberPortions;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setFkRecette($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getFkRecette() === $this) {
                $comment->setFkRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setFkRecette($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getFkRecette() === $this) {
                $evaluation->setFkRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addFkRecette($this);
        }

        return $this;
    }



    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeFkRecette($this);
        }

        return $this;
    }


    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addRecette($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeRecette($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ListedeCourses>
     */
    public function getListedeCourses(): Collection
    {
        return $this->listedeCourses;
    }

    public function addListedeCourse(ListedeCourses $listedeCourse): self
    {
        if (!$this->listedeCourses->contains($listedeCourse)) {
            $this->listedeCourses->add($listedeCourse);
            $listedeCourse->addListe($this);
        }

        return $this;
    }

    public function removeListedeCourse(ListedeCourses $listedeCourse): self
    {
        if ($this->listedeCourses->removeElement($listedeCourse)) {
            $listedeCourse->removeListe($this);
        }

        return $this;
    }


    /**
     * @return Collection<int, User>
     */
    public function getUsersFavorite(): Collection
    {
        return $this->usersFavorite;
    }

    public function addUsersFavorite(User $usersFavorite): self
    {
        if (!$this->usersFavorite->contains($usersFavorite)) {
            $this->usersFavorite->add($usersFavorite);
            $usersFavorite->addFavoriteRecette($this);
        }

        return $this;
    }

    public function removeUsersFavorite(User $usersFavorite): self
    {
        if ($this->usersFavorite->removeElement($usersFavorite)) {
            $usersFavorite->removeFavoriteRecette($this);
        }

        return $this;
    }

    public function getIngredientText(): ?string
    {
        return $this->ingredient_text;
    }

    public function setIngredientText(?string $ingredient_text): self
    {
        $this->ingredient_text = $ingredient_text;

        return $this;
    }


}
