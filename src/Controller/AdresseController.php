<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PhotoProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdresseController extends AbstractController
{
    /**
     * @Route("/adresse_ajouter", name="adresse_ajouter")
     */
    public function adresse_ajouter(Request $request, AdresseRepository $repoadresse, PhotoProduitRepository $repophoto, EntityManagerInterface $manager ): Response
    {

        $adresse = new Adresse;
        $form =$this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user =$this->getUser();
            $adresse->setUser($user);
            $manager->persist ($adresse);
            $manager->flush();
            $route = $_GET ['route'];
            $route = substr($route,1);
            return $this->redirectToRoute($route);
        }

         // on créé un tableau avec les photos pour animer la page
         $photos=$repophoto->findAll();
         $nomphotos = [];
         foreach ( $photos as $photo) {
             $nomPhotos []=$photo->getNom();
         }
         $nomPhotos_str = json_encode($nomPhotos) ;
        
 

        return $this->render('adresse/adresse_ajouter.html.twig', [
            'formAdress' =>$form->createView(),
            'photos'=>$nomPhotos_str
        
        ]);
    }

     /**
     * @Route("/adresse_modifier/{id}", name="adresse_modifier")
     */
    public function adresse_modifier(Adresse $adresse, Request $request, AdresseRepository $repoadresse, PhotoProduitRepository $repophoto, EntityManagerInterface $manager ): Response
    {
        
        $form =$this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $manager->persist($adresse);
           $manager->flush();
           // on récupère la route d'où la requete vient pour y retourner après 
            $route = $_GET ['route'];
            $route = substr($route,1);
            return $this->redirectToRoute($route);
        }
           // on créé un tableau avec les photos pour animer la page
           $photos=$repophoto->findAll();
           $nomphotos = [];
           foreach ( $photos as $photo) {
               $nomPhotos []=$photo->getNom();
           }
           $nomPhotos_str = json_encode($nomPhotos) ;
          


        return $this->render('adresse/adresse_modifier.html.twig', [
            'adresse'=>$adresse,
            'formAdress' =>$form->createView(),
            'photos'=>$nomPhotos_str
        
        ]);
    }

       /**
     * @Route("/adresse_supprimer/{id}", name="adresse_supprimer")
     */
    public function adresse_supprimer(Adresse $adresse, EntityManagerInterface $manager ): Response
    {   

        $manager->remove($adresse);
        $manager->flush() ;
        return $this->redirectToRoute('profil');
    }
}
