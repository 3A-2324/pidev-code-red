<?php

namespace App\Controller;

use App\Entity\Objectif;
use App\Entity\SuiviObjectif;
use App\Entity\User;
use App\Form\ObjectifCRUDType;
use App\Repository\ObjectifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;



class ObjectifController extends AbstractController
{



    #[Route('/graphique_poids', name: 'graphiquepoids')]
    public function graphiquePoids(ManagerRegistry $doctrine): Response
    {
        $token = $this->get('security.token_storage')->getToken();
        if ($token && $token->isAuthenticated()) {
            // Get the user object from the token
            $user = $token->getUser();

            // Do something with the user object
            // For example, get the user's username
            $id = $user->getId();
        }
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        $entityManager = $doctrine->getManager();

        $objectif = $entityManager->getRepository(Objectif::class)->findOneBy(['user' =>$user]);
        $suiviObjectif = $doctrine->getRepository(SuiviObjectif::class)->findOneBy(['objectif' => $objectif]);

        $weight = [];
        $dates = [];

        if ($objectif && $suiviObjectif) {
            $weight[] = $objectif->getWeight();
            $weight[] = $suiviObjectif->getNouveauPoids();

            $dates[] = $objectif->getDatee()->format('Y-m-d');
            $dates[] = $suiviObjectif->getDateSuivi()->format('Y-m-d');
        }

        // Récupérer tous les suivis d'objectifs pour l'objectif donné
        $suivisObjectif = $doctrine->getRepository(SuiviObjectif::class)->findBy(['objectif' => $objectif]);

        foreach ($suivisObjectif as $suivi) {
            $weight[] = $suivi->getNouveauPoids();
            $dates[] = $suivi->getDateSuivi()->format('Y-m-d');
        }

        // Render the result
        return $this->render('objectif_crud/progres.html.twig', [
            'weights' => json_encode($weight),
            'dates' => json_encode($dates),
        ]);
    }


    #[Route('/objectif', name: 'app_objectif')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        $token = $this->get('security.token_storage')->getToken();

// Check if the token exists and is authenticated
        if ($token && $token->isAuthenticated()) {
            // Get the user object from the token
            $user = $token->getUser();

            // Do something with the user object
            // For example, get the user's username
            $id = $user->getId();
        }
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        $datee = $request->request->get('datee');
        if ($request->isMethod('POST')) {
            // Retrieve form data

            $weight = $request->request->get('weight');
            $height = $request->request->get('height');


            $age = $user->getDateDeNaissance()->diff(new \DateTime())->y;
            $gender = $user->getGenre();


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
                $a->setDatee(new \DateTime($datee));
                $a->setCalorie($calories);
                $a->setUser($user);



                // Injectiondu gestionnaire et enregistrement de l'objectif
                $em = $doctrine->getManager();
                $em->persist($a);
                $em->flush();
            }

            // Render the result
            return $this->render('objectif_crud/result.html.twig', [ 'calories' => $calories, 'age'=>$age , 'gender'=> $gender , 'height' => $height , 'weight' =>$weight ]);
        }

        // Render the form
        return $this->render('objectif_crud/indexx.html.twig');
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
        return $this->render('objectif_crud/ajouter.html.twig', ['myForm'=>$form->createView()]);
    }

    #[Route('/affichage', name: 'afficher')]
    public function afficherobjectif(ObjectifRepository $repo,Request $req, PaginatorInterface $paginator ): Response
    {     //repository de l'author il nous fourni des meyhode qui s'exicute sur l'entiter
         $Produit=$repo->findAll();
         $Produit = $paginator->paginate($Produit,
         $req->query->getInt('page',1), 
         2);


        return $this->render('objectif_crud/affichagesuivi.html.twig',['list'=> $Produit]);
        
    }
    #[Route('/supprimer/{id}', name: 'supprimerObjectif')]
    public function removeAuthor($id, ObjectifRepository $repo, ManagerRegistry $managerRegistry)
    {
        // Find the author by ID.
        $author= $repo->find($id);
        $em= $managerRegistry->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute("afficher");
    }
    #[Route('/updateObjectif', name: 'modifierObjectif')]

    public function edit(ManagerRegistry $doctrine, Request $request)
    {
        $token = $this->get('security.token_storage')->getToken();
        if ($token && $token->isAuthenticated()) {
            // Get the user object from the token
            $user = $token->getUser();

            // Do something with the user object
            // For example, get the user's username
            $id = $user->getId();
        }
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        $entityManager = $doctrine->getManager();

        $Objectif = $entityManager->getRepository(Objectif::class)->findOneBy(['user' =>$user]);

        // Create the form using your form type and bind the existing Objectif entity
        $form = $this->createForm(ObjectifCRUDType::class, $Objectif);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the modified Objectif to the database
            $entityManager->flush();

            return $this->render('HomePage.html.twig'); // Redirect to a different route or page
        }
        return $this->render('objectif_crud/ajouter.html.twig', [
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
