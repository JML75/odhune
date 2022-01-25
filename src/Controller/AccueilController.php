<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\PhotoProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/odhune", name="accueil")
     */
    public function accueil(ProduitRepository $repoProduit) :Response
    {

        $produitsArray = $repoProduit->findAll();
        $produitShowcase = [];
        foreach ( $produitsArray as $produit){
            $showcase =$produit->getShowcase();
            if ($showcase) {
               $produitShowcase[] = $produit;
            }
        }

        return $this->render('accueil/accueil.html.twig', [

            "produitsShowcase" => $produitShowcase

        ]);
    }


     /**
     * @Route("/nous", name="nous")
     */
    public function nous (PhotoProduitRepository $repophoto) :Response
    {

        // on créé un tableau avec les photos pour animer la page
        $photos=$repophoto->findAll();
        $nomphotos = [];
        foreach ( $photos as $photo) {
            $nomPhotos []=$photo->getNom();
        }
        $nomPhotos_str = json_encode($nomPhotos) ;

        return $this->render('accueil/nous.html.twig', [

            'photos'=>$nomPhotos_str

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
