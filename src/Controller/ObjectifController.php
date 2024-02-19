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
    public function index(Request $request, ManagerRegistry $doctrine): Response
{
    if ($request->isMethod('POST')) {
        // Retrieve form data
        $weight = $request->request->get('weight');
        $height = $request->request->get('height');
        $age = $request->request->get('age');
        $gender = $request->request->get('gender');
        $activityLevel = $request->request->get('activity_level');
        $choix = $request->request->get('objectif');
        

        // Perform calories calculation (you can replace this with your own formula)
        $calories = $this->calculateCalories($weight, $height, $age, $gender, $activityLevel);
       
        // Ajout de l'objectif après le calcul des calories
        if ($calories !== null) {
            $a = new Objectif();
            
            // Remplissage des données de l'objectif
            // Assurez-vous de définir les valeurs appropriées pour l'objectif en fonction des données que vous avez
            // récupérées dans le formulaire ou calculées ci-dessus.

            $a->setAge($age);
            $a->setSexe($gender);
            $a->setWeight($weight);
            $a->setHeight($height);
            $a->setActivityLevel($activityLevel);
            $a->setChoix($choix);
            $a->setCalorie($calories);

            // Injectiondu gestionnaire et enregistrement de l'objectif
            $em = $doctrine->getManager();
            $em->persist($a);
            $em->flush();
        }

        // Render the result
        return $this->render('templates_back/result.html.twig', [ 'calories' => $calories,  ]);
    }

    // Render the form
    return $this->render('templates_back/indexx.html.twig');
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
        return $this->render('templates_back/objectif_crud/ajouter.html.twig', ['myForm'=>$form->createView()]);
    }

    #[Route('/affichage', name: 'afficher')]
    public function afficherobjectif(ObjectifRepository $repo): Response
    {     //repository de l'author il nous fourni des meyhode qui s'exicute sur l'entiter
         $Produit=$repo->findAll();
        return $this->render('templates_back/objectif_crud/affichagesuivi.html.twig',['list'=> $Produit]);
        
    }
    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function removeAuthor($id, ObjectifRepository $repo, ManagerRegistry $managerRegistry)
    {
    // Find the author by ID.
    $author= $repo->find($id);
    $em= $managerRegistry->getManager();
    $em->remove($author);
    $em->flush();
    return $this->redirectToRoute("afficher");
}
#[Route('/update/{id}', name: 'modifier')]

public function edit(ManagerRegistry $doctrine, Request $request, $id)
{
    $entityManager = $doctrine->getManager();
    $Objectif = $entityManager->getRepository(Objectif::class)->find($id);

    // Create the form using your form type and bind the existing Objectif entity
    $form = $this->createForm(ObjectifCRUDType::class, $Objectif);

    // Handle the form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Save the modified Objectif to the database
        $entityManager->flush();

        return $this->redirectToRoute('afficher'); // Redirect to a different route or page
    }
    return $this->render('templates_back/objectif_crud/ajouter.html.twig', [
        'myForm' => $form->createView(),
        'list' => $Objectif,
    ]);

    
}


   
    private function calculateCalories($weight, $height, $age, $gender, $activityLevel)

{
    // Formule simple pour illustrer le calcul des calories
    // Vous pouvez remplacer cela par une formule plus précise
    if ($gender === 'male') {
        $baseCalories = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } else {
        $baseCalories = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    }

    $calories = $baseCalories * $activityLevel;

    return $calories;
}

    
}





