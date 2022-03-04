<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Repository\AdresseRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PhotoProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("profil", name="profil")
     */
    public function profil(PhotoProduitRepository $repophoto, AdresseRepository $repoadresse)
    {
        /* la methode getUser de Abstract controller (permet de récupérer l'objet User provenant de la table user de l'utilisateur connecté
        */
        $user = $this->getUser();
        $adresse = $repoadresse->findBy(array('user'=>$user));

        // on créé un tableau avec les photos pour animer la page
        $photos=$repophoto->findAll();
        $nomphotos = [];
        foreach ( $photos as $photo) {
            $nomPhotos []=$photo->getNom();
        }
        $nomPhotos_str = json_encode($nomPhotos) ;
        return $this->render('user/profil.html.twig',[
            'user' => $user,
            'adresses'=>$adresse,
            'photos'=>$nomPhotos_str
        ]);
    }


    /**
     * @Route("profil/modifier", name="profil_modifier")
     */
    public function profil_modifier(Request $request, EntityManagerInterface $manager,PhotoProduitRepository $repophoto): Response
    {
        $user = $this->getUser(); 
        $form =$this->createForm(UserType::class, $user, ['profil' => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            
            $manager->persist($user);
            $manager->flush();
           
            return $this->redirectToRoute("profil");
        }



        // on créé un tableau avec les photos pour animer la page
        $photos=$repophoto->findAll();
        $nomphotos = [];
        foreach ( $photos as $photo) {
            $nomPhotos []=$photo->getNom();
        }
        $nomPhotos_str = json_encode($nomPhotos) ;
       
        return $this->render('user/profil_modifier.html.twig', [
            'user' => $user,
            'photos'=>$nomPhotos_str,
            "formUser" =>$form->createView()

        ]);
    }

    /**
 * @Route("/mot_de_passe/modifier" , name="password_modifier")
 */

 public function password_modifier( Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder, PhotoProduitRepository $repophoto)
 {
   $user =$this->getUser(); // objet de l'utilisateur connecté
    $passwordUpdate = new PasswordUpdate;

    $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate );
    $form->handleRequest($request);

  
    
        if ($form->isSubmitted() && $form->isValid()) 
        { 

            if(!$encoder->isPasswordValid($user,$passwordUpdate->getOldPassword()))
            // on teste si l'ancien mot de passe est différent de celui mis dans le formulaire 
            // le test est fait avec l'objet $encoder de la class setPasswordHasherInterface avec la methode isPasswordValid 
            //qui compare le mot de passe dans $user ( de la bdd) avec l'ancien mot de passe rentré dans le formulaire qui n'est pas crypté

            {
                $form->get('oldPassword')->addError(new FormError("L'ancien mot de passe est incorrect"));
                // Rappel  chaque input à un label , un widget ,un help et un error
                // ici on rajoute une erreur au champ 'oldPassword' grace à la methode addError de la class FormInterface
                

            }
            else 
            {   
        
                $hash = $encoder->hashPassword($user,$passwordUpdate->getNewPassword());
                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('profil');
            }
            
        
        }

        // on créé un tableau avec les photos pour animer la page
        $photos=$repophoto->findAll();
        $nomphotos = [];
        foreach ( $photos as $photo) {
            $nomPhotos []=$photo->getNom();
        }
        $nomPhotos_str = json_encode($nomPhotos) ;

     return $this->render('user/password_modifier.html.twig', [
         "formPassword" =>$form->createView(),
         'photos'=> $nomPhotos_str
     ]);
 }

    /**
     * @Route("live", name="live")
     */
    public function live(): Response
    {
        return $this->render('user/watchLive.html.twig', []);
    }

 


}//fin de class
