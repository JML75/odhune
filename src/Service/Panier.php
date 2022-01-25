<?php 

namespace App\Service;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier 
{


    public $session;
    public $repoProduit;
    public $manager;

    public function __construct(SessionInterface $session, ProduitRepository $repoProduit, EntityManagerInterface $manager)
        //  on instance les classes SessionInterface, ProduitRepository EntityManagerInterface grace aux dépendences 
        {
            // lorsqu'on instance la classe Panier on injecte l'objet $session de la classe SessionInterface dasn la propriéré $session on pourra ainsi accéder aux méthodes de ceytte dernière classe , de même pour les 2 autres 
            $this->session = $session;
            $this->repoProduit = $repoProduit;
            $this->manager = $manager;
        }


        public function creationPanier()
        {
            $panier = [
                'id_produit' => [],
                'Produit' => [],
                'nomPhoto' => [],
                "couleur" => [],
                "quantité" => [],
                "prix" => [],
                "reduction" => []
              
              
            ];
     
            return $panier;
        }



        public function add($id_produit,$produit, $nomPhoto, $couleur, $quantite, $reduction,$prix)
        {
    
            $panierSession = $this->session->get('panier'); // equivalent = $_SESSION ['panier]
    
            if(empty($panierSession)) // si $_SESSION ['panier] n'existe pas on le crée
            {
                $newPanier = $this->creationPanier(); //création du tableau vide panier
                $this->session->set('panier', $newPanier);// on insère le tableau vide dans $_SESSION ['panier']
                $panierSession = $this->session->get('panier'); //on le récupère dans la variable $panierSession 
            }
    
    
            // avant d'insérer un produit dans le panier on regarde s'il y est déjà, et s'il y est à quel endroit

    
            // fonction PHP  array_search [valeur recherchée ,tableau ]
            // Retourne la position dans un tableau par rapport à la valeur recherchée
            // si la valeur n'existe pas, la fonction retourne false

            $position_produit = array_search( $id_produit ,$panierSession ["id_produit"]);

            

            // ce produit existe déjà dans le panier on rajoute le contenu de $quantity
            if(is_int($position_produit)) // contient la position par rapport à l'id
            { 
                        $panierSession["quantite"][$position_produit] += $quantite;
                        $this->session->set('panier', $panierSession); 
            }
            else // le produit n'existe pas dans le panier, on génère une nouvelle position dans les 6 tableaux
            {
                $panierSession["id_produit"][] = $id_produit;
                $panierSession["produit"][] = $produit;
                $panierSession["nomPhoto"][] = $nomPhoto;
                $panierSession["couleur"][] = $couleur;
                $panierSession["quantite"][] = $quantite;
                $panierSession["reduction"][] = $reduction;
                $panierSession["prix"][] = $prix;
                
                $this->session->set('panier', $panierSession);
            }

             //on met le panier sous forme de ligne produit et on le stock danssession
             $panier= [];
             for ($i=0 ;$i < count($panierSession ['id_produit']) ; $i++){
                 $panier[$i]['id_produit'] = $panierSession ['id_produit'][$i];
                 $panier[$i]['produit'] = $panierSession ['produit'][$i];
                 $panier[$i]['nomPhoto'] = $panierSession ['nomPhoto'][$i];
                 $panier[$i]['couleur'] = $panierSession ['couleur'][$i];
                 $panier[$i]['quantite'] = $panierSession ['quantite'][$i];
                 $panier[$i]['reduction'] = $panierSession ['reduction'][$i];
                 $panier[$i]['prix'] = $panierSession ['prix'][$i];
             }

             $this->session->set('panier_ligne',$panier);
           
             // on met à jour la variable du badge shopping cart 
             $nbItems = count($panier);
            $this->session->set('panier_item', $nbItems);
            
        } 

    public function vider()
    {
        // $panier = $this->creationPanier();
        // $this->session->set('panier', $panier);
        $this->session->remove("panier");
    }

    public function remove($id_produit)
    {
        $panierSession = $this->session->get('panier');
        $position_produit = array_search($id_produit, $panierSession['id_produit']);

        if(is_int($position_produit))
        {
            array_splice($panierSession['id_produit'], $position_produit, 1);
            array_splice($panierSession['produit'], $position_produit, 1);
            array_splice($panierSession['nomPhoto'], $position_produit, 1);
            array_splice($panierSession['couleur'], $position_produit, 1);
            array_splice($panierSession['quantite'], $position_produit, 1);
            array_splice($panierSession['reduction'], $position_produit, 1);
            array_splice($panierSession['prix'], $position_produit, 1);

            $this->session->set('panier', $panierSession);

        }
         //on met le panier sous forme de ligne produit et on le stock dans session
         $panier= [];
         for ($i=0 ;$i < count($panierSession ['id_produit']) ; $i++){
             $panier[$i]['id_produit'] = $panierSession ['id_produit'][$i];
             $panier[$i]['produit'] = $panierSession ['produit'][$i];
             $panier[$i]['nomPhoto'] = $panierSession ['nomPhoto'][$i];
             $panier[$i]['couleur'] = $panierSession ['couleur'][$i];
             $panier[$i]['quantite'] = $panierSession ['quantite'][$i];
             $panier[$i]['reduction'] = $panierSession ['reduction'][$i];
             $panier[$i]['prix'] = $panierSession ['prix'][$i];
         }

         $this->session->set('panier_ligne',$panier);
       
         // on met à jour la variable du badge shopping cart 
         $nbItems = count($panier);
        $this->session->set('panier_item', $nbItems);
        



    }

    public function montantTotal()
    {
        $panierSession = $this->session->get('panier');
        $total =0;
        for($i=0;$i < count($panierSession['id_produit']); $i++)
        {
            $total = $total + $panierSession['prix'][$i]*$panierSession['quantite'][$i];
        }
        return round($total,2);


    }




}// fin de class