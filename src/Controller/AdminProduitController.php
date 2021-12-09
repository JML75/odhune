<?php

namespace App\Controller;

use App\Entity\PhotoProduit;
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
        $form->handleRequest($request);
       

       if($form->isSubmitted() && $form->isValid()){
       
        $manager->persist ($produit);
        $manager->flush();

        $photoFile = $form ->get('photo')->getData();

        if ($photoFile) {
            for ($i=0 ; $i<count($photoFile); $i++){
                $photo= new PhotoProduit;
                $nomPhoto = uniqid() . "-". date("YmdHis") . "-" . $photoFile[$i]->getClientOriginalName();
                str_replace(' ' , '', $nomPhoto);
                str_replace('/' , '', $nomPhoto);
                // le varchar de la table est 255 on reduit si c'est au-dessus 
                if (strlen($nomPhoto) > 255) {
                    $remove = strlen($nomPhoto)-255;
                    substr ($nomPhoto,$remove);
                }
                $photoFile[$i]->move ($this->getParameter("photos_produit"), $nomPhoto);
                // on recupère le chemin d'accès des photos uploadées défini dans services.yaml  paramètres avec $this->getParameter("nom du paramètre")
                $photo->setNom($nomPhoto);
                $photo->setProduit($produit);
                $photo->setPosition ($i+1);
                $manager->persist($photo);
                $manager->flush();
                $photoFile[$i]=$photo;
            }
        }
          $this->addFlash("success" , "Le produit N° ".$produit->getId(). "a bien été ajouté");
          
          return $this->redirectToRoute('produit_afficher');

       }
 
        return $this->render('admin_produit/produit_ajouter.html.twig', [
            "formProduit" => $form->createView()

        ]);
    }

/*----------------produit supprimer--------------------------------------*/
/**
 * @Route("/gestion_produit/supprimer", name="produit_supprimer")
 */
    public function produit_supptimer(ProduitRepository $repoProduit): Response
    // syntaxe avec les dépendences remplace le  constructeur () $repoProduit = new  ProduitRepository) car les contructions sont variables 


{
        return $this->render('admin_produit/produit_afficher.html.twig');
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