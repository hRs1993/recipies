<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $difficulty;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Igridient", mappedBy="recipies")
     */
    private $igridients;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="recipies")
     */
    private $tags;

    public function __construct()
    {
        $this->igridients = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * @return Collection|Igridient[]
     */
    public function getIgridients(): Collection
    {
        return $this->igridients;
    }

    public function addIgridient(Igridient $igridient): self
    {
        if (!$this->igridients->contains($igridient)) {
            $this->igridients[] = $igridient;
            $igridient->addRecipy($this);
        }

        return $this;
    }

    public function removeIgridient(Igridient $igridient): self
    {
        if ($this->igridients->contains($igridient)) {
            $this->igridients->removeElement($igridient);
            $igridient->removeRecipy($this);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addRecipy($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeRecipy($this);
        }

        return $this;
    }
}
