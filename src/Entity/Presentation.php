<?php

namespace App\Entity;

use App\Repository\PresentationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PresentationRepository::class)
 */
class Presentation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sstitre;

    /**
     * @ORM\Column(type="text")
     */
    private $presentation;

    /**
     * @ORM\OneToMany(targetEntity=PhotoLife::class, mappedBy="presentation", orphanRemoval=true)
     */
    private $photoLives;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function __construct()
    {
        $this->photoLives = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSstitre(): ?string
    {
        return $this->sstitre;
    }

    public function setSstitre(string $sstitre): self
    {
        $this->sstitre = $sstitre;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection|PhotoLife[]
     */
    public function getPhotoLives(): Collection
    {
        return $this->photoLives;
    }

    public function addPhotoLife(PhotoLife $photoLife): self
    {
        if (!$this->photoLives->contains($photoLife)) {
            $this->photoLives[] = $photoLife;
            $photoLife->setPresentation($this);
        }

        return $this;
    }

    public function removePhotoLife(PhotoLife $photoLife): self
    {
        if ($this->photoLives->removeElement($photoLife)) {
            // set the owning side to null (unless already changed)
            if ($photoLife->getPresentation() === $this) {
                $photoLife->setPresentation(null);
            }
        }

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }


}
