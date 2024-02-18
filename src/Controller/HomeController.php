<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('templates_back/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/front', name: 'app_homef')]
    public function indexf(): Response
    {
        return $this->render('templates_front/baseFront.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    
}
