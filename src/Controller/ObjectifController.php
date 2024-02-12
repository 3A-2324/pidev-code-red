<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObjectifController extends AbstractController
{
    #[Route('/objectif', name: 'app_objectif')]
    public function index(): Response
    {
        return $this->render('templates_back/objectif.html.twig', [
            'controller_name' => 'ObjectifController',
        ]);
    }
}
