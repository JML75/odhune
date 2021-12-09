<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/odhune", name="accueil")
     */
    public function accueil() :Response
    {
        return $this->render('accueil/accueil.html.twig', [

        ]);
    }
}
