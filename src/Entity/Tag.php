<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Recipe", inversedBy="tags")
     */
    private $recipies;

    public function __construct()
    {
        $this->recipies = new ArrayCollection();
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

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipies(): Collection
    {
        return $this->recipies;
    }

    public function addRecipy(Recipe $recipy): self
    {
        if (!$this->recipies->contains($recipy)) {
            $this->recipies[] = $recipy;
        }

        return $this;
    }

    public function removeRecipy(Recipe $recipy): self
    {
        if ($this->recipies->contains($recipy)) {
            $this->recipies->removeElement($recipy);
        }

        return $this;
    }
}
