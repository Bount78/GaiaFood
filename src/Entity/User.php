<?php

namespace App\Entity;

use App\Entity\Article;
use App\Entity\Recette;
use App\Entity\ListedeCourses;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $FirstName = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\ManyToMany(targetEntity: Recette::class, inversedBy: 'users')]
    private Collection $recette;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ListedeCourses::class)]
    private Collection $liste_courses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\ManyToMany(targetEntity: Recette::class, inversedBy: 'usersFavorite')]
    #[ORM\JoinTable(name: "user_favorite_recettes")]
    private Collection $favorite_recettes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImage = null;

    public function __construct()
    {
        $this->recette = new ArrayCollection();
        $this->liste_courses = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->favorite_recettes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(?string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(?string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getFullName(): ?string
    {
        $fullName = '';

        if ($this->FirstName) {
            $fullName .= $this->FirstName;
        }

        if ($this->LastName) {
            $fullName .= ' ' . $this->LastName;
        }

        return trim($fullName);
    }


    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecette(): Collection
    {
        return $this->recette;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recette->contains($recette)) {
            $this->recette->add($recette);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        $this->recette->removeElement($recette);

        return $this;
    }

    /**
     * @return Collection<int, ListedeCourses>
     */
    public function getListeCourses(): Collection
    {
        return $this->liste_courses;
    }

    public function addListeCourse(ListedeCourses $listeCourse): self
    {
        if (!$this->liste_courses->contains($listeCourse)) {
            $this->liste_courses->add($listeCourse);
            $listeCourse->setUser($this);
        }

        return $this;
    }

    public function removeListeCourse(ListedeCourses $listeCourse): self
    {
        if ($this->liste_courses->removeElement($listeCourse)) {
            // set the owning side to null (unless already changed)
            if ($listeCourse->getUser() === $this) {
                $listeCourse->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getFavoriteRecettes(): Collection
    {
        return $this->favorite_recettes;
    }

    public function addFavoriteRecette(Recette $favoriteRecette): self
    {
        if (!$this->favorite_recettes->contains($favoriteRecette)) {
            $this->favorite_recettes->add($favoriteRecette);
        }

        return $this;
    }

    public function removeFavoriteRecette(Recette $favoriteRecette): self
    {
        $this->favorite_recettes->removeElement($favoriteRecette);

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $profileImage): self
    {
        $this->profileImage = $profileImage;

        return $this;
    }

}
