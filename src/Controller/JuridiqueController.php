<?php

namespace App\Controller;

use App\Repository\PhotoProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JuridiqueController extends AbstractController
{
    /**
     * @Route("mentionLegales", name="mentionsLegales")
     */
    public function mentionsLegales(PhotoProduitRepository $repophoto): Response
    {

         // on créé un tableau avec les photos pour animer la page
         $photos=$repophoto->findAll();
         $nomphotos = [];
         foreach ( $photos as $photo) {
             $nomPhotos []=$photo->getNom();
         }
         $nomPhotos_str = json_encode($nomPhotos) ;
        return $this->render('juridique/mentions_legales.html.twig', [
            'photos'=> $nomPhotos_str

        ]);
    }


     /**
     * @Route("cgv", name="cgv")
     */
    public function cgv(PhotoProduitRepository $repophoto): Response
    {

         // on créé un tableau avec les photos pour animer la page
         $photos=$repophoto->findAll();
         $nomphotos = [];
         foreach ( $photos as $photo) {
             $nomPhotos []=$photo->getNom();
         }
         $nomPhotos_str = json_encode($nomPhotos) ;
         
        return $this->render('juridique/cgv.html.twig', [
            'photos'=> $nomPhotos_str

        ]);
    }





}//fin de classe
