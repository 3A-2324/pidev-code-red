<?php

namespace App\Controller;


namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\SuiviObjectif;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SuiviObjectifType;
use App\Repository\SuiviObjectifRepository;
use App\Repository\ObjectifRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use App\Entity\Objectif;
class SuiviobjectifController extends AbstractController
{
    #[Route('/ajoutersuivi', name: 'ajouterrr')]
    public function Ajoutersuiviobjectif(Request $req, ManagerRegistry $doctrine, ObjectifRepository $objectifRepository): Response
    {
        $suiviObjectif = new SuiviObjectif();
        $form = $this->createForm(SuiviObjectifType::class, $suiviObjectif);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $objectif = $suiviObjectif->getIdObjectif();

            if ($objectif && $suiviObjectif->getNouveauPoids() !== null) {
                $ancienPoidsObjectif = $objectif->getWeight();
                $nouveauPoidsSuivi = $suiviObjectif->getNouveauPoids();

                if ($ancienPoidsObjectif > $nouveauPoidsSuivi) {
                    $commentaire = 'Perte de poids, bien tu peux dimunier les nombre de calorie pours perte du poids!';
                } elseif ($ancienPoidsObjectif < $nouveauPoidsSuivi) {
                    $commentaire = 'Prise de poids, maintenant pour reste sur le meme poids n augmente pas vos calorie !';
                } else {
                    $commentaire = 'Pas de changement de poids,un peu de travail s il vous plait .';
                }

                $suiviObjectif->setCommentaire($commentaire);
                $em->persist($suiviObjectif);
                $em->flush();

                // Redirection vers la page nouvelle_page avec le commentaire
                return $this->redirectToRoute('nouvelle_page', ['commentaire' => $commentaire]);
            }
        }

        $objectifs = $objectifRepository->findAll();

        return $this->render('suiviobjectif/ajouterr.html.twig', [
            'myForm' => $form->createView(),
            'objectifs' => $objectifs,
        ]);
    }

    #[Route('/nouvellepage', name: 'nouvelle_page')]
    public function nouvellePage(Request $request): Response
    {
        $commentaire = $request->get('commentaire', 'Aucun commentaire disponible.');

        return $this->render('templates_back/objectif_crud/affichage.html.twig', ['commentaire' => $commentaire]);
    }

  #[Route('/affichagesuivi', name: 'affichersuivi')]
    public function affichersuiviobjectif(SuiviObjectifRepository $repo): Response
    {     //repository de l'author il nous fourni des meyhode qui s'exicute sur l'entiter
         $Produit=$repo->findAll();
        return $this->render('templates_back/objectif_crud/affichesuivicrud.html.twig',['list'=> $Produit]);
        
    }
     #[Route('/supprimerr/{id}', name: 'supprimerr')]
    public function removeAuthor($id, SuiviObjectifRepository $repo, ManagerRegistry $managerRegistry)
    {
    // Find the author by ID.
    $author= $repo->find($id);
    $em= $managerRegistry->getManager();
    $em->remove($author);
    $em->flush();
    return $this->redirectToRoute("affichersuivi");
}

#[Route('/updatesuivi/{id}', name: 'modifierr')]

public function edit(ManagerRegistry $doctrine, Request $request, $id)
{
    $entityManager = $doctrine->getManager();
    $SuiviObjectif = $entityManager->getRepository(SuiviObjectif::class)->find($id);

    // Create the form using your form type and bind the existing Objectif entity
    $form = $this->createForm(SuiviObjectifType::class, $SuiviObjectif);

    // Handle the form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Save the modified Objectif to the database
        $entityManager->flush();

        return $this->redirectToRoute('afficher'); // Redirect to a different route or page
    }

    // Fetch the list of 'objectifs' from the database
    $objectifs = $entityManager->getRepository(Objectif::class)->findAll();

    return $this->render('templates_back/objectif_crud/ajoutersuivi.html.twig', [
        'myForm' => $form->createView(),
        'list' => $SuiviObjectif,
        'objectifs' => $objectifs, // Pass 'objectifs' variable to the template
    ]);
}
/*public function comparerObjectif(): void
{
    if ($this->getIdObjectif()->getChoix() === 'perdre_du_poids') {
        if ($this->getNouveauPoids() < $this->getIdObjectif()->getWeight()) {
            $this->setCommentaire('bien joué');
        } else {
            $this->setCommentaire('échec, pas d\'objectif');
        }
    } elseif ($this->getIdObjectif()->getChoix() === 'gagner_du_poids') {
        if ($this->getNouveauPoids() > $this->getIdObjectif()->getWeight()) {
            $this->setCommentaire('un peu mal');
        } else {
            $this->setCommentaire('bien');
        }
    }
}*/



}
