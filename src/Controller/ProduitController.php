<?php

namespace App\Controller;

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
    // syntaxe avec les dépendences    remplace le  constructeur () $repoProduit = new  ProduitRepository) car les constructions sont variables 
   


    {
        $produitsArray = $repoProduit->findAll();
        
        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsArray
        ]);
  
    }

    /**
     * @Route("/optique", name="optique")
     */
    public function optique(ProduitRepository $repoProduit): Response
    // syntaxe avec les dépendences    remplace le  constructeur () $repoProduit = new  ProduitRepository) car les constructions sont variables 
   
    {
        $produitsOptique = $repoProduit->findBy(array('categorie'=>'Optique'));
        
        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsOptique
        ]);
  
    }

    /**
     * @Route("/solaire", name="solaire")
     */
    public function solaire(ProduitRepository $repoProduit): Response
    // syntaxe avec les dépendences    remplace le  constructeur () $repoProduit = new  ProduitRepository) car les constructions sont variables 
   
    {
        $produitsSolaire = $repoProduit->findBy(array('categorie'=>'Solaire'));

        
        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsSolaire
        ]);
  
    }

    /**
     * @Route("/capsule", name="capsule")
     */
    public function capsule(ProduitRepository $repoProduit): Response
    // syntaxe avec les dépendences    remplace le  constructeur () $repoProduit = new  ProduitRepository) car les constructions sont variables 
   
    {
        $produitsCapsule = $repoProduit->findBy(array('categorie'=>'Capsule'));
        
        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsCapsule
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

    public function fiche_produit($id, ProduitRepository $repoProduit): Response
    {
        $produit = $repoProduit->find($id);
        return $this->render('produit/fiche_produit.html.twig', [
            'produit'=>$produit
        ]);
  
    }

    


} //fin de class
