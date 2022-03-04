<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PhotoProduitRepository;
use App\Security\SecurityAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder,UserAuthenticatorInterface $userAuthenticator, SecurityAuthenticator $authenticator,PhotoProduitRepository $repophoto): Response
    {
        $user = new User;
        $form =$this->createForm(UserType::class, $user, ['inscription' => true]);
        // on passe l'option inscription au UserType pour différencoier les builders
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $password=$user->getpassword();
            $hash = $encoder->hashPassword($user,$password);
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            $userAuthenticator->authenticateUser($user,$authenticator, $request);
          
            return $this->redirectToRoute("accueil");
        }
        // on créé un tableau avec les photos pour animer la page
        $photos=$repophoto->findAll();
        $nomphotos = [];
        foreach ( $photos as $photo) {
            $nomPhotos []=$photo->getNom();
        }
        $nomPhotos_str = json_encode($nomPhotos) ;
       


        return $this->render('security/inscription.html.twig', [
            "formUser" =>$form->createView(),
            'photos'=>$nomPhotos_str
        ]);
    }

     /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion(PhotoProduitRepository $repophoto)
    {
        $photos=$repophoto->findAll();
        $nomphotos = [];
        foreach ( $photos as $photo) {
            $nomPhotos []=$photo->getNom();
        }
        $nomPhotos_str = json_encode($nomPhotos) ;
       

        return $this->render("security/connexion.html.twig", [
            'photos'=>$nomPhotos_str
        ]);
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
    public function roles(SessionInterface $session)
    { 
    if($session->get('commande')){
        return $this->redirectToRoute("commande");

    }

        if($this->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("accueil");
        }
        elseif($this->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute("accueil");
        }
        elseif($this->isGranted('ROLE_REV'))
        {
            return $this->redirectToRoute("accueil");
        }
    }

} //fin de classe



