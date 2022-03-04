<?php

namespace App\Controller;

use App\Repository\AccueilRepository;
use App\Repository\ProduitRepository;
use App\Repository\PhotoLifeRepository;
use App\Repository\PhotoProduitRepository;
use App\Repository\PresentationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(ProduitRepository $repoProduit, AccueilRepository $repoAccueil) :Response
    {

        // on récupère  les produits mis en avant 
        $produitsShowcase = $repoProduit->findBy(array('showcase'=> true));
        

        // on récupére la photo/video du mode large active

        $fileLgActive= $repoAccueil->findBy(array('active'=> true ,'large'=>true));
    
        // si aucune n'est active on prend la première non active ( il y en a au moins 1)
        
        if (count( $fileLgActive) == 0 ) {
            $fileLgActive = $repoAccueil->findBy(array('large'=>true));
        }

        $fileLgActive= $fileLgActive[0];


         // on récupére la photo/video du mode SmartPhone Tablette

         $fileMdActive= $repoAccueil->findBy(array('active'=> true ,'large'=>false));

         // si aucune n'est active on prend la première non active ( il y en a au moins 1)
         
         if (count( $fileMdActive) == 0 ) {
             $fileMdActive = $repoAccueil->findBy(array('large'=>false));
         }


         $fileMdActive = $fileMdActive[0];
 
         $nomFileLg= $fileLgActive->getNomPhotoVideo();
         $typeFileLg=  $fileLgActive->getVideo();

         $nomFileMd = $fileMdActive->getNomPhotoVideo();
         $typeFileMd = $fileMdActive->getVideo();



        return $this->render('accueil/accueil.html.twig', [
            "produitsShowcase" => $produitsShowcase,
            "fileMd"=>$nomFileMd,
            "typeMd"=>$typeFileMd,
            "fileLg"=>$nomFileLg,
            "typeLg"=>$typeFileLg

        ]);
    }


     /**
     * @Route("/nous", name="nous")
     */
    public function nous (PhotoLifeRepository $repophoto, PresentationRepository $repopresentation) :Response
    
    {
        $presentationActives =$repopresentation->findBy(array('active'=> true));


        // si aucune présentation n'est active on prend la première
        if (count($presentationActives) == 0) {
            $presentationArray= $repopresentation->findAll();
            $presentationActives=$presentationArray [0];
        }

        // $photos =  $presentationActive->getPhotoLives();
        // $nomphotos = [];
        // foreach ( $photos as $photo) {
        //     $nomPhotos []=$photo->getNom();
        // }
        // $nomPhotos_str = json_encode($nomPhotos) ;

        return $this->render('accueil/nous.html.twig', [
            'presentationActives'=>$presentationActives,

        ]);
    }


     /**
     * @Route("/magasin", name="magasin")
     */
    public function magasin (PhotoProduitRepository $repophoto) :Response
    {

        // on créé un tableau avec les photos pour animer la page
        $photos=$repophoto->findAll();
        $nomphotos = [];
        foreach ( $photos as $photo) {
            $nomPhotos []=$photo->getNom();
        }
        $nomPhotos_str = json_encode($nomPhotos) ;

        return $this->render('accueil/magasin.html.twig', [

            'photos'=>$nomPhotos_str

        ]);
    }
}
