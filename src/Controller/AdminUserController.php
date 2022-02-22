<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PhotoProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**-------------------------------------------------------
     *-------------------  AFFICHER La liste
     *-------------------------------------------------------
    */


    /**
     * @Route("admin/user", name="user_afficher")
     */
    public function admin_user( UserRepository $repoUsers)
    {
        $usersArray = $repoUsers->findAll();
        return $this->render('admin_user/user_afficher.html.twig', [
            "users" => $usersArray
        ]);
    }

    /**-------------------------------------------------------
     *-------------------  Supprimer 
     *-------------------------------------------------------
    */

    /**
     * @Route ("admin/user_supprimer/{id}" , name= "user_supprimer")
     */
    public function user_supprimer (User $user,EntityManagerInterface $manager )

    {
        $userId = $user->getId();
        $manager->remove($user);
        $manager->flush() ;

        return $this->render('admin_user/user_afficher.html.twig');
    }


    /**-------------------------------------------------------
     *-------------------  Modifier 
     *-------------------------------------------------------
    */

    /**
     * @Route ("admin/user_modifier/{id}" , name= "user_modifier")
     */
    public function user_modifier (User $user,Request $request,EntityManagerInterface $manager ,PhotoProduitRepository $repophoto)
    {
        $form = $this->createForm(UserType::class, $user, ["userAdmin"=>true]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())

        {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("user_afficher");

        }
          // on créé un tableau avec les photos pour animer la page
          $photos=$repophoto->findAll();
          $nomphotos = [];
          foreach ( $photos as $photo) {
              $nomPhotos []=$photo->getNom();
          }
          $nomPhotos_str = json_encode($nomPhotos) ;
         

        return $this->render('admin_user/user_modifier.html.twig', [
            'user'=>$user,
            'photos'=>$nomPhotos_str,
            'formUser'=>$form->createView()

        ]);
        
    }

}// fin de la classe
