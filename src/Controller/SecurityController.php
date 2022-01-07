<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User;
        $form =$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $password=$user->getpassword();
            $hash = $encoder->hashPassword($user,$password);
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash("success", $user->getPrenom(). " votre inscription a bien été enregistrée , vous pouvez vous connecter");
            return $this->redirectToRoute("connexion");
        }

        return $this->render('security/inscription.html.twig', [
            "formUser" =>$form->createView()
        ]);
    }

     /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion()
    {
        return $this->render("security/connexion.html.twig");
    }

      /**
    * @Route("/deconnexion", name="deconnexion")
     */

    public function deconnexion(){}


     /**
     * Lorsqu'un utilisateur vient de se connecter, il est redirigé sur la fonction roles()
     * security.yaml : default_target_path
     * qui permet de checker le role de l'utilisateur
     * et sera redirigé sur une route en fonction de son rôle 
     * 
     * @Route("/roles", name="roles")
     */
    public function roles()
    {
        if($this->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("back_office");
        }
        elseif($this->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute("profil");
        }
    }

} //fin de classe



