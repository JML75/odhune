<?php

namespace App\Entity;

use App\Repository\AccueilRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AccueilRepository::class)
 */
class Accueil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Renseigner le titre de la présentation")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_photo_video;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $video;

    /**
     * @ORM\Column(type="boolean")
     */
    private $large;

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

    public function getNomPhotoVideo(): ?string
    {
        return $this->nom_photo_video;
    }

    public function setNomPhotoVideo(string $nom_photo_video): self
    {
        $this->nom_photo_video = $nom_photo_video;

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

    public function getVideo(): ?bool
    {
        return $this->video;
    }

    public function setVideo(bool $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getLarge(): ?bool
    {
        return $this->large;
    }

    public function setLarge(bool $large): self
    {
        $this->large = $large;

        return $this;
    }
}
