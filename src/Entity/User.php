<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields = {"email"},
 * message = "Cet email et déjà associé à un compte")
 */

class User implements UserInterface, PasswordAuthenticatedUserInterface

{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email,   ex : nom@domaine.com."
     * )
     */
    private $email;

    // pattern ="/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$€@%_])([-+!*$€@%_\w]{8,15})$/",

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Veuillez saisir un mot de passe")
     * @Assert\Regex(
     *  pattern ="//",
     *  message="8-15 caractères, au moins: 1 majuscule 1 minuscule  1 car. parmi - + ! * $ € @ % _"
     * )
     */

    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Veuillez saisir votre nom")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Veuillez saisir votre prenom")
     */
    private $prenom;

     // avec l'implémentation de la classe UserInterface il faut créer la propriété "role" et l'implementer dans la base de donnée

    /**
     * @ORM\Column(type="json")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\OneToMany(targetEntity=Adresse::class, mappedBy="user", orphanRemoval=true)
     */
    private $adresses;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_inscription;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="user")
     */
    private $commandes;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }  // ROLE_USER est défini dans security.yaml, il peut y avoir plusieurs Roles avec des hierarchies c'est donc un objet qui sera un tableau en BDD SQL , par defaut il est au niveau hierarchique le plus bas

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
     * @return string the hashed password for this user
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

   

    /**
     * l'entité User est différente des autres entités , car c'est par cette entité qu'un utilisateur va pourvoir s'authentifier, Symfony a déja créé toute la sécurité et il demande à la classe User d'implémenter la classe UserInterface 
     * Il faut donc Implementer des méthodes de cette classe 
     */

    // identification, dire qu'elle propriété sert à l'identification
    // on doit l'implementer (demnandée par la classe implementée) mais elle n'est plus utilisée depuis la version  5.3 on utilise la getUserIdentifier , il faut nénamoins l'implémenter pendant la transition

    public function getUsername(): ?string {
        return $this->email;
    }
    // identification, dire qu'elle propriété sert à l'identification
    public function getUserIdentifier(): ?string {
       return $this->email;
   }

   //Roles 
   public function getRoles(): ?array {
       $roles = $this->roles;
       return array_unique($roles); 
   }

   public function setRoles($role) 
   {
       $this->roles = $role;
       return $this ;
   }

   public function getSalt(){} // renvoie la string password non encodé que l'utilisateur a saisi

   
   public function eraseCredentials(){} // nettoie le mdp

   

   /**
    * @return Collection|Adresse[]
    */
   public function getAdresses(): Collection
   {
       return $this->adresses;
   }

   public function addAdress(Adresse $adress): self
   {
       if (!$this->adresses->contains($adress)) {
           $this->adresses[] = $adress;
           $adress->setUser($this);
       }

       return $this;
   }

   public function removeAdress(Adresse $adress): self
   {
       if ($this->adresses->removeElement($adress)) {
           // set the owning side to null (unless already changed)
           if ($adress->getUser() === $this) {
               $adress->setUser(null);
           }
       }

       return $this;
   }

   public function getDateInscription(): ?\DateTimeInterface
   {
       return $this->date_inscription;
   }

   public function setDateInscription(\DateTimeInterface $date_inscription): self
   {
       $this->date_inscription = $date_inscription;

       return $this;
   }

   /**
    * @return Collection|Commande[]
    */
   public function getCommandes(): Collection
   {
       return $this->commandes;
   }

   public function addCommande(Commande $commande): self
   {
       if (!$this->commandes->contains($commande)) {
           $this->commandes[] = $commande;
           $commande->setUser($this);
       }

       return $this;
   }

   public function removeCommande(Commande $commande): self
   {
       if ($this->commandes->removeElement($commande)) {
           // set the owning side to null (unless already changed)
           if ($commande->getUser() === $this) {
               $commande->setUser(null);
           }
       }

       return $this;
   }




}// fin de classe
