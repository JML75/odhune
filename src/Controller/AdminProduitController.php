<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\PhotoProduit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\SplFileInfo;
use App\Repository\PhotoProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProduitController extends AbstractController
{
    /* ce controller est pour pour faire le CRUD
    

/*----------------produit afficher--------------------------------------*/
    /**
     * @Route("/gestion_produit/afficher", name="produit_afficher")
     */
    public function produit_afficher(ProduitRepository $repoProduit): Response
     // syntaxe avec les dépendences    remplace le  constructeur () $repoProduit = new  ProduitRepository) car les constructions sont variables 
    

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
        

        // gestion du retour de la DropZone photo
        $dropZone_dir = $this->getParameter("photos_dropzone");
        $remove = false;
        if(isset($_POST['remove'])){
            $remove = $_POST['remove'];
        }
       

        if ($remove == false) {
            if (isset($_FILES['file'])) {

            $photoFile = $_FILES ['file'];
            

            for ($i=0 ; $i<count($photoFile['name']); $i++){
               
                $path  = $photoFile ['tmp_name'][$i];
                $mimetype = $photoFile ['type'][$i];
                $nomPhoto = $photoFile['name'][$i];
                $photo = new UploadedFile($path,$nomPhoto,$mimetype);
            
                $photo->move($dropZone_dir, $nomPhoto);                                    
                }
            }
        }

        // gestion de la suppression d'une photo dans la dropzone 
        if (isset($_POST['remove']) && $_POST['remove'] == true){

        }
        if ($remove == true) {
            $filename = $dropZone_dir."/".$_POST['name'];
            unlink($filename); exit;
        }
     
        

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid()){
            $manager->persist ($produit);
            $manager->flush();

            $photoFile = scandir($dropZone_dir);

            if ($photoFile) {
                for ($i=2 ; $i<count($photoFile); $i++){
                    //les 2 premiers éléments du scandir ne sont pas les fichiers
                    // $upload_dir = $this->getParameter("photos_dropzone");
                    $photo= new PhotoProduit;
                   
                    $nomPhoto = uniqid() . "-". date("YmdHis") . "-" .substr ($photoFile[$i],2);

                    str_replace(' ' , '', $nomPhoto);
                    str_replace('/' , '', $nomPhoto);
                    // le varchar de la table est 255 on reduit si c'est au-dessus 
                    if (strlen($nomPhoto) > 255) {
                        $remove = strlen($nomPhoto)-255;
                        substr ($nomPhoto,$remove);
                    }
                    
                    $upload_dir=$this->getParameter("photos_produit");


                    copy($dropZone_dir.'/'.$photoFile[$i], $upload_dir.'/'. $nomPhoto);
                    unlink($dropZone_dir.'/'.$photoFile[$i]);

                    $photo->setNom($nomPhoto);
                    $photo->setProduit($produit);
                    $photo->setPosition ($i-1);
                  
                    $manager->persist($photo);
                    $manager->flush();
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
 * @Route("/gestion_produit/supprimer/{id}", name="produit_supprimer")
 */
    public function produit_supprimer(Produit $produit, EntityManagerInterface $manager) : Response
    {
    if ($produit->getPhotoProduits()){

    $photos = $produit->getPhotoProduits()->getValues();
  

    for ($i=0; $i < count($photos); $i++) 
    { 
        unlink($this->getParameter("photos_produit")."/".$photos[$i]);
        $manager->remove($photos[$i]);
        $manager->flush();
    }

    }   
    $produitId = $produit->getId();
    $manager->remove($produit);
    $manager->flush() ;
    $this->addFlash ("success", "Le  produit N° $produitId a bien été supprimée");

        return $this->redirectToRoute('produit_afficher');
    }

/*----------------produit modifier--------------------------------------*/
    /**
    * @Route("/gestion_produit/modifier/{id}", name="produit_modifier")
     */
    public function produit_modifier(Produit $produit): Response
    {
       // c'est une dépendence $produit sera le produit avec l'id :id 
       
       $form = $this->createForm(ProduitType::class, $produit);
        return $this->render('admin_produit/produit_modifier.html.twig', [
            'produit'=>$produit,
            'formProduit'=>  $form->createView()
        ]);
    }




} // fin de class