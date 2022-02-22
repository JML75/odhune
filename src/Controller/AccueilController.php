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
     * @Route("/odhune", name="accueil")
     */
    public function accueil(ProduitRepository $repoProduit, AccueilRepository $repoAccueil) :Response
    {

        $produitsArray = $repoProduit->findAll();
        $produitShowcase = [];
        foreach ( $produitsArray as $produit){
            $showcase =$produit->getShowcase();
            if ($showcase) {
               $produitShowcase[] = $produit;
            }
        }

        $filesAccueil= $repoAccueil->findAll();
        $activeFile = [];
        foreach ( $filesAccueil as $file){
            $active =$file->getActive();
            if ($active) {
               $activeFile[] = $file;
            }
        }
        if (count( $activeFile) !== 0 ) {

            $file = $activeFile[0];
        
            $nomFile= $file->getNomPhotoVideo();
            $typeFile= $file->getVideo();

        }else { 
            $file = $filesAccueil[0];
            $nomFile= $file->getNomPhotoVideo();
            $typeFile= $file->getVideo();
        }


        return $this->render('accueil/accueil.html.twig', [
            "produitsShowcase" => $produitShowcase,
            "file"=>$nomFile,
            "type"=>$typeFile

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
