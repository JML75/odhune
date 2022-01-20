<?php

namespace App\Controller;

use App\Service\Panier;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function panier(SessionInterface $session): Response     
    {



        $panierSession = $session->get('panier');

         //on met le panier sous forme de ligne produit
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

         // on mais le panier sous forme ligne dans la $_SESSION pour l'updater en javascript dans la gestion du panier

         $_SESSION ['panier_ligne'] = $panier;

         $panier_str = json_encode($panier);
        return $this->render('panier/panier.html.twig', [
            'panier' => $panier,
            'panierstr'=> $panier_str
          
        ]);
    }


    /**
     * @Route("/panier/ajouter", name="panier_ajouter")
     */
    public function panier_ajouter(Request $request, ProduitRepository $repoProduit, Panier $panier): Response
    {

        $id = $request->request->get("id");
        $produit= $repoProduit->find($id);
        $nomProduit=$produit->getNom();
        $photo = $produit->getPhotoProduits()->first();
        $nomPhoto= $photo->getNom();
        $quantite = $request->request->get("qty");
        $couleur= $request->request->get("color");
        $id_produit = $id.substr($couleur,0,1);
        $reduction = 0;
        $prix = $produit->getPrixPubTtc();
        $panier->add($id_produit,$produit, $nomPhoto, $couleur, $quantite, $reduction, $prix);

        return $this->redirectToRoute('fiche_produit', [
            'id'=>$id
        ]);
    }

    /**
     * @Route("/panier/changer", name="panier_changer")
     */
    public function panier_changer(Request $request, ProduitRepository $repoProduit, Panier $panier, SessionInterface $session): Response
    {
        $panierSession = $session->get('panier');

        $quantite = $_POST ['qty'];
        $id_produit = $_POST ['id_produit'];
        $position_produit = array_search( $id_produit ,$panierSession ["id_produit"]);
        $panierSession["quantite"][$position_produit] = $quantite;

        
        $panier->session->set('panier', $panierSession); 



        return $this->redirectToRoute('panier', [
            
        ]);
    }


}//fin de classe
