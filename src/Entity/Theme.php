<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Subject::class, mappedBy: 'category')]
    private Collection $subjects;

    // #[ORM\ManyToOne(inversedBy: 'category')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Subject::class, mappedBy: 'theme')]
    private Collection $subjectsTheme;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->subjectsTheme = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): static
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->setCategory($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        if ($this->subjects->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getCategory() === $this) {
                $subject->setCategory(null);
            }
        }

        return $this;
    }

    // public function getUser(): ?User
    // {
    //     return $this->user;
    // }

    // public function setUser(?User $user): static
    // {
    //     $this->user = $user;

    //     return $this;
    // }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubjectsTheme(): Collection
    {
        return $this->subjectsTheme;
    }

    public function addSubjectsTheme(Subject $subjectsTheme): static
    {
        if (!$this->subjectsTheme->contains($subjectsTheme)) {
            $this->subjectsTheme->add($subjectsTheme);
            $subjectsTheme->setTheme($this);
        }

        return $this;
    }

    public function removeSubjectsTheme(Subject $subjectsTheme): static
    {
        if ($this->subjectsTheme->removeElement($subjectsTheme)) {
            // set the owning side to null (unless already changed)
            if ($subjectsTheme->getTheme() === $this) {
                $subjectsTheme->setTheme(null);
            }
        }

        return $this;
    }
}
