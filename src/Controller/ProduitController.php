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
