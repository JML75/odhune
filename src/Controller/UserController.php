<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("profil", name="profil")
     */
    public function profil()
    {
        /* la methode getUser de Abstract controller (permet de récupérer l'objet User provenant de la table user de l'utilisateur connecté
        */ 
        $user = $this->getUser(); 
        return $this->render('user/profil.html.twig');
}

}//fin de class
