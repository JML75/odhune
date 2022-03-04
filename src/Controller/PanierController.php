<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\UserType;
use App\Service\Panier;
use App\Repository\ProduitRepository;
use App\Security\SecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function panier(SessionInterface $session): Response     
    {

        //on récupére le panier ligne de produit 
        $panier = $session->get('panier_ligne');
         // on le convertit  to string pour le récupérer et l'exploiter en javascript 
         $panier_str = json_encode($panier);
    
        return $this->render('panier/panier.html.twig', [
            'panier' => $panier,
            'panierstr'=> $panier_str
          
        ]);
    }


    /**
     * @Route("/panier/ajouter", name="panier_ajouter")
     */
    public function panier_ajouter(SessionInterface $session,Request $request, ProduitRepository $repoProduit, Panier $panier): Response
    {

        $id = $request->request->get("id");
        $produit= $repoProduit->find($id);
        $reduction = 0;
        $prix = $produit->getPrixPubTtc();
        $couleur=$produit->getCouleur();
        $nomProduit=$produit->getNom();
        $photo=$produit->getPhotoProduits()->first();
        $nomPhoto= $photo->getNom();//par defaut

        // on recherche la photo de face à la bonne couleur pour présenter dans le panier
        $lettre_coul = substr($couleur,0,1);
        $photos = $produit->getPhotoProduits();
        foreach($photos as $photo ) {
            $nom_Photo = $photo->getNom();
            $lettre_photo = substr($nom_Photo,-5,1);
            if ($lettre_photo == $lettre_coul){
                $nomPhoto =  $nom_Photo;
           }
        }

        $quantite = $request->request->get("qty");
        $prix = $produit->getPrixPubTtc();


        $panier->add($id,$produit, $nomPhoto, $quantite, $reduction, $prix);


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
   

        return $this->redirectToRoute('panier', []);
    }

    /**
     * @Route("panier/supprimer", name="panier_supprimer")
     */
    public function supprimer(Panier $panier): Response
    {
        $id_produit = $_POST ['id_produit'];
        $panier->remove($id_produit);

        return $this->redirectToRoute('panier', []);
    }

   

}//fin de classe
