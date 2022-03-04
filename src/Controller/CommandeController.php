<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adresse;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\LigneCommande;
use App\Security\SecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class CommandeController extends AbstractController
{
    /**
     * @Route("/preparer-commande", name="preparer-commande")
     */
    public function preparerCommande(EntityManagerInterface $manager, Request $request, Security $security, UserPasswordHasherInterface $encoder,UserAuthenticatorInterface $userAuthenticator, SecurityAuthenticator $authenticator,  SessionInterface $session): Response
    {

        $user = $this->getUser();
        $session->set('commande',true); // booleen pour changer le redirect dans la fonction Role appelée après connexion

        if ($user == null){  // si l'utilisateur n'est pas inscrit
            $user = new User;
            $form =$this->createForm(UserType::class, $user, ['inscription' => true]);
            $form->handleRequest($request);
 
            if ($form->isSubmitted() && $form->isValid()) {
 
             $password=$user->getpassword();
             $hash = $encoder->hashPassword($user,$password);
             $user->setPassword($hash);
             $manager->persist($user);
             $manager->flush();
 
             $userAuthenticator->authenticateUser($user,$authenticator, $request);
 
             return $this->redirectToRoute('commande');
             } 
         
             return $this->render('security/inscription_connexion.html.twig', [
                 'formUser' => $form->createView(),

          ]);


        }

        $commande = new Commande;
        $commande->setUser($user);
        


        
        $form = $this->createForm(CommandeType::class, $commande);

        $panier = $session->get('panier_ligne');
        $panierStr = json_encode($panier);
    
  
        return $this->render('commande/commande_preparer.html.twig', [
            'panier' => $panier,
            'panierStr' => $panierStr,
            'user'=>$user

        ]);
    }


    /**
     * @Route("ajouter-commande", name="ajouter-commande")
     */
    public function ajouterCommande(): Response
    {
        // return $this->render('$0.html.twig', []);
    }
}// fin de classe
