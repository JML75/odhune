<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AadminProduitController extends AbstractController
{
    /**
     * @Route("/aadmin/produit", name="aadmin_produit")
     */
    public function index(): Response
    {
        return $this->render('aadmin_produit/index.html.twig', [
            'controller_name' => 'AadminProduitController',
        ]);
    }
}
