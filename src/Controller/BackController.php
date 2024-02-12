<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/Dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('templates_back/base.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }
}
