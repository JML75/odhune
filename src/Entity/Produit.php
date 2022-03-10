<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; /** permet de rajouter des contraintes sur les saisies des fomulaires avant d'envoyer en bdd , c'est plus sécurisé que les required en front office, Assert est un alias pour constraints */

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
     * @Assert\NotBlank (message="Renseigner le nom du produit")
     * @Assert\Length(
     * min = 1,
     * max = 30,
     * minMessage = "1 caratère minimum",
     * maxMessage = "30 caratères maximum"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank (message="Renseigner la référence")
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Renseigner la catégorie")
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matiere;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank (message="Renseigner la couleur")
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
     * @Assert\NotBlank (message="Renseigner le prix")
     * @Assert\PositiveOrZero  (message="le prix doit être positif")
     */
    private $prix_pub_ttc;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank (message="Renseigner le prix")
     * @Assert\PositiveOrZero (message="le prix doit être positif")
     */
    private $prix_rev_ht;

    /**
     * @ORM\OneToMany(targetEntity=PhotoProduit::class, cascade={"persist"} ,mappedBy="produit", orphanRemoval=true)
     */
    private $photoProduits;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showcase;

    /**
     * @ORM\Column(type="text")
     */
    private $entretien;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="produit",orphanRemoval=true)
     */
    private $ligneCommandes;



    public function __construct()
    {
        $this->photoProduits = new ArrayCollection();
        $this->ligneCommandes = new ArrayCollection();
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

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getShowcase(): ?bool
    {
        return $this->showcase;
    }

    public function setShowcase(bool $showcase): self
    {
        $this->showcase = $showcase;

        return $this;
    }

    public function getEntretien(): ?string
    {
        return $this->entretien;
    }

    public function setEntretien(string $entretien): self
    {
        $this->entretien = $entretien;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes[] = $ligneCommande;
            $ligneCommande->setProduit($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getProduit() === $this) {
                $ligneCommande->setProduit(null);
            }
        }

        return $this;
    }

}
