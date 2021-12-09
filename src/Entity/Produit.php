<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $forme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $taille;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_pub_ttc;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_rev_ht;

    /**
     * @ORM\OneToMany(targetEntity=PhotoProduit::class, mappedBy="produit", orphanRemoval=true)
     */
    private $photoProduits;

    public function __construct()
    {
        $this->photoProduits = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(string $forme): self
    {
        $this->forme = $forme;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getPrixPubTtc(): ?float
    {
        return $this->prix_pub_ttc;
    }

    public function setPrixPubTtc(float $prix_pub_ttc): self
    {
        $this->prix_pub_ttc = $prix_pub_ttc;

        return $this;
    }

    public function getPrixRevHt(): ?float
    {
        return $this->prix_rev_ht;
    }

    public function setPrixRevHt(float $prix_rev_ht): self
    {
        $this->prix_rev_ht = $prix_rev_ht;

        return $this;
    }

    /**
     * @return Collection|PhotoProduit[]
     */
    public function getPhotoProduits(): Collection
    {
        return $this->photoProduits;
    }

    public function addPhotoProduit(PhotoProduit $photoProduit): self
    {
        if (!$this->photoProduits->contains($photoProduit)) {
            $this->photoProduits[] = $photoProduit;
            $photoProduit->setProduit($this);
        }

        return $this;
    }

    public function removePhotoProduit(PhotoProduit $photoProduit): self
    {
        if ($this->photoProduits->removeElement($photoProduit)) {
            // set the owning side to null (unless already changed)
            if ($photoProduit->getProduit() === $this) {
                $photoProduit->setProduit(null);
            }
        }

        return $this;
    }

}
