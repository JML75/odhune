<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\PhotoProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /* pour  faire les routes  catalogue et fiche produit */


    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogue(ProduitRepository $repoProduit): Response
    {
        $produitsArray = $repoProduit->findAll();
        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsArray,
            "categorie" => 'Tous'
        ]);
    }

    /**
     * @Route("/optique", name="optique")
     */
    public function optique(ProduitRepository $repoProduit): Response
    {
        $produitsOptique = $repoProduit->findBy(array('categorie'=>'Optique'));
        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsOptique,
            "categorie" => 'Optique'
        ]);
    }

    /**
     * @Route("/solaire", name="solaire")
     */
    public function solaire(ProduitRepository $repoProduit): Response  
    {
        $produitsSolaire = $repoProduit->findBy(array('categorie'=>'Solaire'));  
        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsSolaire,
            "categorie" => 'Solaire'
        ]);
    }

    /**
     * @Route("/capsule", name="capsule")
     */
    public function capsule(ProduitRepository $repoProduit): Response  
    {
        $produitsCapsule = $repoProduit->findBy(array('categorie'=>'Capsule'));       
        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsCapsule,
            "categorie" => 'Capsule'
        ]);
    }


    /**
     * @Route("/showcase", name="showcase")
     */

    public function showcase(ProduitRepository $repoProduit): Response
    
   
    {
        $produitsArray = $repoProduit->findAll();
        $produitShowcase = [];
        foreach ( $produitsArray as $produit){
            $showcase =$produit->getShowcase();
            if ($showcase) {
               $produitShowcase[] = $produit;
            }
        }
    
        return $this->render('produit/showcase.html.twig', [
            "produitsShowcase" => $produitShowcase
        ]);
  
    }


    /**
     * @Route("/fiche_produit/{id<\d+>}", name="fiche_produit")
     * 
     * <\d+> est pour limité le paramètre aux entiers
     */

    public function fiche_produit($id, ProduitRepository $repoProduit,Request $request): Response
    {
        // si la requête vient du catalogue 
        $produit = $repoProduit->find($id);
        $nomProduit=$produit->getNom();
      
        // si la requête vient de la fiche produit pour une autre couleur
        $couleurChange= $request->get('couleurChange');
        if($couleurChange) {
            $produit = $repoProduit->findBy(array('nom' => $nomProduit, 'couleur' => $couleurChange));
            $produit=$produit[0]; // findBy retourne un tableau
        }
        
         // on cherche toutes les couleurs disponibles pour les retourner à la fiche produit
         $couleursProduit =  $repoProduit->findBy(array('nom'=>$nomProduit ));
         $couleursDispo =[];
         foreach( $couleursProduit as $couleurProduit){
             $couleursDispo []=$couleurProduit->getCouleur();
         }

         // on recupère les photos du produit concerné pour alimenter les vignettes du caroussel c'est plus direct pour les récupérer en javascript que sous forme de varianle TWIG
         $photos = $produit->getPhotoProduits();
         $nomPhotos =[];
         foreach ( $photos as $photo) {
             $nomPhotos []=$photo->getNom();
         }
        $nomPhotos_str = json_encode($nomPhotos) ;

        return $this->render('produit/fiche_produit.html.twig', [
            'produit'=>$produit,
            'photos'=>$nomPhotos_str,
            'couleurs'=>$couleursDispo
        ]);
    }

    


} //fin de class
