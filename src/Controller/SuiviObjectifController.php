<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuiviObjectifController extends AbstractController
{
    #[Route('/suivi/objectif', name: 'app_suivi_objectif')]
    public function index(): Response
    {
        return $this->render('templates_back/suiviobjectif.html.twig', [
            'controller_name' => 'SuiviObjectifController',
        ]);
    }
}
