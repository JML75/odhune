<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminProduitController extends AbstractController
{
    /* ce controller est pour pour faire le CRUD
    

/*----------------produit afficher--------------------------------------*/
    /**
     * @Route("/gestion_produit/afficher", name="produit_afficher")
     */
    public function produit_afficher(ProduitRepository $repoProduit): Response
     // syntaxe avec les dépendences    remplace le  constructeur () $repoProduit = new  ProduitRepository) car les contructions sont variables 
    

    {
        $produitsArray = $repoProduit->findAll();
        return $this->render('admin_produit/produit_afficher.html.twig', [
            "produits" => $produitsArray
        ]);
    }


/*----------------produit ajouter--------------------------------------*/
    /**
     * @Route("/gestion_produit/ajouter", name="produit_ajouter")
     */
    public function produit_ajouter(Request $request, EntityManagerInterface $manager): Response
    {
        $produit = new Produit;
      
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handlerequest($request); 

       if($form->isSubmitted() && $form->isValid()){
          $manager->persist($produit);
          $manager->flush();
          $this->addFlash("success" , "Le produit N° ".$produit->getId(). "a bien été ajouté");
          
          return $this->redirectToRoute('produit_afficher');

       }
 
        return $this->render('admin_produit/produit_ajouter.html.twig', [
            "formProduit" => $form->createView()

        ]);
    }

/*----------------produit modifier--------------------------------------*/
    /**
     * @Route("/gestion_produit/mofifier", name="produit_modifier")
     */
    public function produit_modifier(): Response
    {
        return $this->render('admin_produit/produit_modifier.html.twig', [

        ]);
    }

/*----------------produit ajouter fin ---------------------------------*/

} // fin de class