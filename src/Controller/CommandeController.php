<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use Payplug\Payment;
use Payplug\Payplug;
use App\Form\UserType;
use App\Entity\Adresse;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\LigneCommande;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Address;
use App\Repository\AdresseRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Security\SecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
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

        $adresses=$user->getAdresses();
        $adresses= $adresses->toArray();
        $panier = $session->get('panier_ligne');
        $panierStr = json_encode($panier);
    
  
        return $this->render('commande/commande_preparer.html.twig', [
            'panier' => $panier,
            'panierStr' => $panierStr,
            'user'=>$user,
            'adresses'=>$adresses

        ]);
    }

    /**
     * @Route("payer-commande", name="payer-commande")
     */
    public function payerCommande(SessionInterface $session, Request $request, AdresseRepository $repoadresse): Response

    {
        
        // payplug
        $user=$this->getUser();
        $montant=$request->get('montant-total');
        $adresse_id= $request->get('adresse-livraison');

        Payplug::init(array(
            'secretKey' => 'sk_test_yujTJiSNZQAnj77GUAuRe',
            'apiVersion' => '2019-08-06',
          ));
        $montantCts =(float)$montant*100;
        $prenom= $user->getPrenom();
        $nom= $user->getNom();
        $mail=$user->getEmail();
        $adresse = $repoadresse->find( $adresse_id);
        $cp = $adresse->getCp();
        $rue = $adresse->getAdresse();
        $ville =$adresse->getVille();
       

        $payment = Payment::create(array(
            'amount'         => $montantCts,
            'currency'       => 'EUR',
            'save_card'      => false,
            'billing'          => array(
                'first_name'   => $prenom,
                'last_name'    => $nom,
                'email'        => $mail,
                'address1'     => $rue,
                'postcode'     => $cp,
                'city'         => $ville,
                'country'      => 'FR',
                'language'     => 'fr'
            ),
            'shipping'          => array(
                'first_name'   => $prenom,
                'last_name'    => $nom,
                'email'        => $mail,
                'address1'     => $rue,
                'postcode'     => $cp,
                'city'         => $ville,
                'country'      => 'FR',
                'language'     => 'fr',
                'delivery_type' => 'BILLING'
            ),
            'hosted_payment' => array(
                'return_url' =>'https://sleepy-carson.185-13-64-115.plesk.page/ajouter-commande',
                'cancel_url' => 'https://sleepy-carson.185-13-64-115.plesk.page/panier'
            ),
            'notification_url' => 'https://sleepy-carson.185-13-64-115.plesk.page/ajouter-commande',
            'metadata'        => array(
                'customer_id' => '193405'
            )
        ));

        $payment_url = $payment->hosted_payment->payment_url;
        $payment_id = $payment->id;
        

        // // on recupére les éléments du formulaire + le payment_id on les stocke dans un objet dans la session

        $data_commande = [
            'livraison'=> $request->get('cout-livraison'),
            'adresse_id'=> $request->get('adresse-livraison'),
            'montant'=>  $request->get('montant-total'),
            'payment_id'=> $payment_id
        ]; 
       

        $session->set('data_commande',$data_commande);
     

        return $this->redirect($payment_url);

        // return $this->redirect('ajouter-commande');

    
    }

    /**
     * @Route("ajouter-commande", name="ajouter-commande")
     */

    public function ajouterCommande(Request $request, Session $session, AdresseRepository $repoadresse, EntityManagerInterface $manager, CommandeRepository $repocommande,ProduitRepository $repoproduit, MailerInterface $mailer): Response
    {   
       
       
        // on récupère les variables
       
        $user=$this->getUser();
     
        $data_commande = $session->get('data_commande');
        $payment_id =$data_commande['payment_id'];
      
        $adresse_id = $data_commande['adresse_id'];
        $adresse = $repoadresse->find( $adresse_id);
        $montant= $data_commande['montant'];
        $livraison = $data_commande['livraison'];
        $panier = $session->get('panier_ligne');
        $date = new \DateTimeImmutable('now');
        $delai = new \DateInterval('P5D');
        $date_livraison = $date->add($delai );
        
        
        //on entregistre la commande 
        $commande = new Commande;
        $commande->setUser($user);
        $commande->setAdresseLivraison($adresse);
        $commande->setMontant($montant);
        $commande->setMontantHT($montant / 1.2);
        $commande->setCoutLivraison($livraison);
        $commande->setDateCommande($date);
        $commande->setDateLivraison($date_livraison);
        $commande->setStatut('preparation');
        $commande->setNumero('');
        $commande->setPaiement( $payment_id);
  
        $manager->persist($commande);
        $manager->flush();

      

        // on recupère l'id commande pour créé le numero de commande
        $id_commande =$commande->getId();
        $commande = $repocommande->find($id_commande);
        $numero=date('Ymd').'-'.$id_commande;
        $commande->setNumero($numero);
        $manager->persist($commande);
        $manager->flush();

        // on remplit les lignes de commandes
        foreach ( $panier as $ligne){
            // necessaire pour éviter de persister $produit commme un nouveau produit
            $produit= $ligne ["produit"];
            $id_produit= $produit->getId();
            $produit = $repoproduit->find($id_produit);
            //--------------------------------------------------

            $ligneCommande= new LigneCommande;
            $ligneCommande->setCommande($commande);
            $ligneCommande->setProduit($produit);
            $ligneCommande->setQty($ligne ['quantite']);
            $ligneCommande->setPrix($ligne ['prix']);
            $sous_total= $ligne ['quantite']*$ligne ['prix'];
            $ligneCommande->setSstotal($sous_total);
            $manager->persist($ligneCommande);
            $manager->flush();
        }

        // on envoie l'email de confirmation
        $dateLivraison =   date_format($date_livraison,"d/m/Y");
        $email = (new TemplatedEmail())
        ->from(new Address('contact@odhune.com', 'ODHUNE'))
        ->to($user->getEmail())
        ->subject('Confirmation de commande ODHUNE')
        ->htmlTemplate('commande/email_confirm_commande.html.twig')
        ->context([
            'commande'=>$commande,
            'user'=> $user,
            'panier'=>$panier,
            'date'=>$dateLivraison
    
        ])
    ;
    
        $mailer->send($email);

        // on nettoie la session 
        $session ->remove ('panier');
        $session ->remove ('panier_ligne');
        $session ->remove ('panier_item');
        $session ->remove ('commande');
        $session ->remove ('data_commande');
       
        
        return $this->redirectToRoute('accueil');
    }
}// fin de classe
