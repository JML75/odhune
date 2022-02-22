<?php

namespace App\Controller;

use App\Entity\PhotoLife;
use App\Entity\Presentation;
use App\Form\PresentationType;
use App\Repository\PresentationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPresentationController extends AbstractController
{
    /**
     * @Route("/admin/presentation_ajouter", name="presentation_ajouter")
     */
    public function presentation_ajouter(Request $request, EntityManagerInterface $manager): Response
    {

        $presentation = new Presentation;


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
            $filename = $dropZone_dir."/".$_POST['name'];
            unlink($filename); exit;

        }
    // creation et gestion du formulaire 
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist ($presentation);
            $manager->flush();
            $photoFile = scandir($dropZone_dir);

            if ($photoFile) {
                
                for ($i=2 ; $i<count($photoFile); $i++){
                    //les 2 premiers éléments du scandir ne sont pas les fichiers
                    // $upload_dir = $this->getParameter("photos_dropzone");
                    $photo= new PhotoLife;
                
                
                    $nomPhoto = uniqid() . "-". date("YmdHis") . "-" .substr ($photoFile[$i],2);

                    str_replace(' ' , '', $nomPhoto);
                    str_replace('/' , '', $nomPhoto);
                    // le varchar de la table est 255 on reduit si c'est au-dessus 
                    if (strlen($nomPhoto) > 255) {
                        $remove = strlen($nomPhoto)-255;
                        substr ($nomPhoto,$remove);
                    }
                    
                    $upload_dir=$this->getParameter("photos_life");


                    copy($dropZone_dir.'/'.$photoFile[$i], $upload_dir.'/'. $nomPhoto);
                    unlink($dropZone_dir.'/'.$photoFile[$i]);

                    $photo->setNom($nomPhoto);
                    $photo->setPresentation($presentation);
                    $photo->setPosition ($i-1);
                    $manager->persist($photo);
                    $manager->flush();
                }
            }
        
            return $this->redirectToRoute('presentation_afficher');

        }


        return $this->render('admin_presentation/presentation_ajouter.html.twig', [
            'formPresentation' =>$form->createView(),
           
        ]);
    }

    /**
     * @Route("/admin/presentation_afficher", name="presentation_afficher")
     */

    public function presentation_afficher(PresentationRepository $repopresentation): Response
    {
        $presentations = $repopresentation->findAll();

        return $this->render('admin_presentation/presentation_afficher.html.twig', [
            'presentations' => $presentations
           
        ]);
    }



    /**
     * @Route("/admin/presentation_modifier/{id}", name="presentation_modifier")
     */

    public function presentation_modifier(Presentation $presentation, EntityManagerInterface $manager , Request $request ): Response
    { 
        // on recupère les photos pour charger la dropZone 
       
       $photoLives = $presentation->getPhotoLives()->getValues();
       $nbPhoto = count($photoLives);
       $upload_dir=$this->getParameter("photos_life");
       $dropZone_dir = $this->getParameter("photos_dropzone");

       foreach ($photoLives as $photo) {
        $nomPhoto = $photo->getNom();
        $photosize = filesize($upload_dir.'/'.$nomPhoto);
        $photoDropzone ['name']=$nomPhoto;
        $photoDropzone ['size']= $photosize;
        $result[]= $photoDropzone ;
        $reloadPhoto = json_encode($result);
       };


       $remove = false; 

       if(isset($_POST['remove'])){ // si la photo a été supprimée dans la dropZone
           $remove = $_POST['remove'];
       }

       if ($remove == false) { //  on sauve la photo dans /uploadDropzonz
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


      // gestion de la suppression d'une photo en bdd  depuis la dropzone 
       if (isset($_POST ['remove']) && $_POST ['remove']==true){
        //on verifie si c'est une photo nouvelle ou une photo stockée en bdd
        $photoNew= true;
        
        foreach ($photoLives as $photo){
            if ($_POST ['name']== $photo->getNom()){
                $filename = $upload_dir."/".$_POST['name'];
                unlink($filename);
                $photoNew= false;
                $nbPhoto=$nbPhoto-1;
                $manager->remove($photo);
                $manager->flush();
            }
        }
        
        if($photoNew == true){ // si c'est une photo nouvelle dans la dropzone
            $filename = $dropZone_dir."/".$_POST['name'];
            unlink($filename);
           
        }else {exit;}
            
        };

        $form= $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($presentation);
            $manager->flush();

            $photoFile = scandir($dropZone_dir);


            if ($photoFile) {
                for ($i=2 ; $i<count($photoFile); $i++){
                    // les 2 premiers éléments du scandir ne sont pas les fichiers
                    // $upload_dir = $this->getParameter("photos_dropzone");
                    $photo = new PhotoLife;
                
                    $nomPhoto = uniqid() . "-". date("YmdHis") . "-" .substr ($photoFile[$i],2);

                    str_replace(' ' , '', $nomPhoto);
                    str_replace('/' , '', $nomPhoto);
                    // le varchar de la table est 255 on reduit si c'est au-dessus 
                    if (strlen($nomPhoto) > 255) {
                        $remove = strlen($nomPhoto)-255;
                        substr ($nomPhoto,$remove);
                    }
                    
                    $upload_dir=$this->getParameter("photos_life");


                    copy($dropZone_dir.'/'.$photoFile[$i], $upload_dir.'/'. $nomPhoto);
                    unlink($dropZone_dir.'/'.$photoFile[$i]);

                    $photo->setNom($nomPhoto);
                    $photo->setPresentation($presentation);
                    $photo->setPosition ($nbPhoto+$i-1);
                
                    $manager->persist($photo);
                    $manager->flush();
                }
            }
            return $this->redirectToRoute('presentation_afficher');

        }




        
        return $this->render('admin_presentation/presentation_modifier.html.twig', [
            'presentation'=>$presentation,
            'formPresentation'=>  $form->createView(),
            'reloadPhoto'=> $reloadPhoto,

           
        ]);
    }


    /**
     * @Route("/admin/presentation_supprimer/{id}", name="presentation_supprimer")
     */

    public function presentation_supprimer(Presentation $presentation, EntityManagerInterface $manager, PresentationRepository $repopresentation): Response

    { 
            $presentations = $repopresentation->findAll();

            if (count($presentations)> 0) {

                if ($presentation->getPhotoLives() ) {

                    $photos= $presentation->getPhotoLives()->getValues();
        
                    for ($i=0; $i < count($photos); $i++) 
                    { 
                        unlink($this->getParameter("photos_life")."/".$photos[$i]);
                        $manager->remove($photos[$i]);
                        $manager->flush();
                    }
            
                    }  
                    
                    $presentationId = $presentation->getId();
                    $manager->remove($presentation);
                    $manager->flush() ;
                    return $this->redirectToRoute('presentation_afficher');

            }
           
                
                return $this->redirectToRoute('presentation_afficher');
            }


    /**
     * @Route("/admin/presentation_active/{id}", name="presentation_active")
     */

    public function presentation_active(Presentation $presentation, EntityManagerInterface $manager, PresentationRepository $repoPresentation): Response

    { 
          
                    if ($presentation->getActive()) {$presentation->setActive(false);}

                    else {$presentation->setActive(true);}  

                    $manager->persist($presentation);
                    $manager->flush() ;
            
                   
                return $this->redirectToRoute('presentation_afficher');
            }




}// fin de class
