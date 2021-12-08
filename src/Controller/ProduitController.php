<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            "produits" => $produitsArray

        ]);
  
    }


} //fin de class
