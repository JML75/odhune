<?php

namespace App\Controller;

use App\Entity\Accueil;
use App\Form\AccueilType;
use App\Repository\AccueilRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\DependencyInjection\Security\UserProvider\EntityFactory;

class AdminAccueilController extends AbstractController
{
    /**
     * @Route("/admin/accueil", name="accueil_afficher")
     */
    public function accueil_afficher(AccueilRepository$repoAccueil): Response
    {
        $accueils =$repoAccueil->findAll();


        return $this->render('admin_accueil/accueil_afficher.html.twig', [
            "accueils"=>$accueils
        
        ]);
    }

    // ------------------------Ajouter une image / video d'accueil----------------------

    /**
     * @Route("/admin/accueil_ajouter", name="accueil_ajouter")
     */
    public function accueil_ajouter(EntityManagerInterface $manager, Request $request): Response
    {
         //gestion du retour de la DropZone photo on initilise les varibles
         $dropZone_dir = $this->getParameter("photos_dropzone");// répertoire temporaire défini  dans service.yaml
         $remove = false;

 
         //on traite la une eventuelle requête AJAX de suppression
 
         if(isset($_POST['remove'])){
             $remove = $_POST['remove'];
         }
        
         // si la requête n'est pas un requête de suppression
         if ($remove == false) {
        
             if (isset($_FILES['file'])) {
    
                 $files = $_FILES ['file'];
           
             
             for ($i=0 ; $i<count($files['name']); $i++){
                
                 $path  = $files ['tmp_name'][$i];
                 $mimetype = $files ['type'][$i];
                 $nomfile = $files['name'][$i];
                 $file = new UploadedFile($path,$nomfile,$mimetype);
                 // on crée un objet de type uploadedFile pour utiliser la méthode move
             
                 $file->move($dropZone_dir, $nomfile); //on transfère le fichier dans le répertoire temporaire                                
                 }
             }
         }
 
         // gestion de la suppression d'une photo dans la dropzone 
         if (isset($_POST['remove']) && $_POST['remove'] == true){
             $filename = $dropZone_dir."/".$_POST['name'];
             unlink($filename); exit;
 
         }
     

        $accueil= new Accueil;
        $form = $this->createForm(AccueilType::class , $accueil);
        $form->handleRequest(($request));
        if($form->isSubmitted() && $form->isValid()){
            
            $scandir= scandir($dropZone_dir);
            $file =$scandir[2];
            $nomFile = uniqid() . "-". date("YmdHis") . "-" .substr ($file,2);
            str_replace(' ' , '', $nomFile);
            str_replace('/' , '', $nomFile);

            // le varchar de la table est 255 on reduit si c'est au-dessus 
            if (strlen($nomFile) > 255) {
                $remove = strlen($nomFile)-255;
                substr ($nomFile,$remove);
            }
            $upload_dir=$this->getParameter("photo_video_accueil");
            copy($dropZone_dir.'/'.$file, $upload_dir.'/'. $nomFile);
            unlink($dropZone_dir.'/'.$file);

            $accueil->setnomPhotoVideo($nomFile);
            $manager->persist($accueil);
            $manager->flush();
            return $this->redirectToRoute('accueil');
   
         }
            
      
        return $this->render('admin_accueil/accueil_ajouter.html.twig', [
            'formAccueil'=>$form->createView()   
        ]);
        }

// ------------------------Supprimer une image / video d'accueil----------------------
    /**
     * @Route("/admin/accueil_supprimer/{id}", name="accueil_supprimer")
     */

    public function accueil_supprimer(Accueil $accueil, EntityManagerInterface $manager, AccueilRepository $repoAccueil): Response

    { 
        //on verifie si la requête est pour le mode large ou pour le mode responsive 
        if ( $accueil->getLarge()){
            // on fait attention qu'il reste toujours au moins une image/video d'accueil pour le mode large
            $accueilLg = $repoAccueil->findBy(array('large'=>true));
            if (count($accueilLg)> 0) {
                $manager->remove($accueil);
                $manager->flush() ;
            }
        else { // la requête est pour le mode responsive
            // on fait attention qu'il reste toujours au moins une image/video d'accueil pour le mode responsive
            $accueilMd = $repoAccueil->findBy(array('large'=>false));
            if (count($accueilMd)> 0) {
                $manager->remove($accueil);
                $manager->flush() ;
            }
        }

        }
                
        return $this->redirectToRoute('accueil_afficher');
    }


    /**
     * @Route("/admin/accueil_active/{id}", name="accueil_active")
     */

    public function accueil_active(Accueil $accueil, EntityManagerInterface $manager, AccueilRepository $repoAccueil): Response

    { 
          
                    if ($accueil->getActive()) {$accueil->setActive(false);}

                    else {$accueil->setActive(true);}  

                    $manager->persist($accueil);
                    $manager->flush() ;
            
                   
                return $this->redirectToRoute('accueil_afficher');
            }

} //fin de classe
