<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* Cette route placée avant la classe permet d'intégrer à chaque route du controller le *prefix "/admin" on le fait ici pour securiser les routes car /admin est déclaré dans *security.yaml avec les 2 lignes ci dessous
*access_control:
*        - { path: ^/admin, roles: ROLE_ADMIN }
*/

/** 
*@Route("/admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/back_office", name="back_office")
     */
    public function back_office(): Response
    {
        return $this->render('admin/back_office.html.twig');
    }
}
