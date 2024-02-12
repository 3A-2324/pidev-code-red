<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Objectif;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ObjectifRepository;
use App\Form\ObjectifCRUDType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class ObjectifController extends AbstractController
{
    #[Route('/objectif', name: 'app_objectif')]
    public function index(): Response
    {
        return $this->render('templates_back/objectif.html.twig', [
            'controller_name' => 'ObjectifController',
        ]);
    }
    #[Route('/ajouterobjectif', name: 'ajouter')]
    public function Ajouterobjectif(Request $req, ManagerRegistry $doctrine): Response
    {      //objet à insérer
        $a=new Objectif();
        //instancier la classe du formulaire
        $form=$this->createForm(ObjectifCRUDType::class, $a);
        // $form->add('Save_me', SubmitType::class);
        //form is submitted or not + remplissage de l'objet $a
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid())
        {   //injection un gestionnaire
            $em=$doctrine->getManager();
            //créer la requête d'ajout
            $em->persist($a);
            //exécuter la requête
            $em->flush();
           // return $this->redirectToRoute('afficher');
        }
        return $this->render("objectif_crud/ajouter.html.twig", ['myForm'=>$form->createView()]);
    }

    #[Route('/affichage', name: 'afficher')]
    public function afficherobjectif(ObjectifRepository $repo): Response
    {     //repository de l'author il nous fourni des meyhode qui s'exicute sur l'entiter
         $Produit=$repo->findAll();
        return $this->render('templates_back/objectif_crud/affichage.html.twig',['list'=> $Produit]);
        
    }
    /*#[Route('/supprimer/{id}', name: 'supprimer')]
    public function removeAuthor($id, AuthorRepository $repo, ManagerRegistry $managerRegistry)
    {
    // Find the author by ID.
    $author= $repo->find($id);
    $em= $managerRegistry->getManager();
    $em->remove($author);
    $em->flush();
    return $this->redirectToRoute("afficher");
}
#[Route('/update/{id}', name: 'modifier')]
public function edit(ManagerRegistry $doctrine, Request $request,$id)
{
    $entityManager = $doctrine->getManager();
    $author = $entityManager->getRepository(Author::class)->find($id);
    $form = $this->createForm(AuthorType::class, $author);

    // Handle the form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Save the modified book to the database
        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('afficher'); // Redirect to a different route or page
    }

    return $this->render('auther/add.html.twig', [ 'myForm' => $form->createView(),'list'=> $author,]);
}*/
}


