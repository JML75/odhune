<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdresseRepository::class)
 */
class Adresse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pays;

    /**
     * @ORM\Column(type="boolean")
     */
    private $facturation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $livraison;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $complement_nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $complement_adresse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getFacturation(): ?bool
    {
        return $this->facturation;
    }

    public function setFacturation(bool $facturation): self
    {
        $this->facturation = $facturation;

        return $this;
    }

    public function getLivraison(): ?bool
    {
        return $this->livraison;
    }

    public function setLivraison(bool $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }
    public function getComplementNom(): ?string
    {
        return $this->complement_nom;
    }

    public function setComplementNom(string $complement_nom): self
    {
        $this->complement_nom = $complement_nom;

        return $this;
    }

    public function getComplementAdresse(): ?string
    {
        return $this->complement_adresse;
    }

    public function setComplementAdresse(string $complement_adresse): self
    {
        $this->complement_adresse = $complement_adresse;

        return $this;
    }



    public function __toString()
    {
        return $this->nom;
    }

   



}//fin de classe