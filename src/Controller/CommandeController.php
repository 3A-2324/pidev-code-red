<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User;
use App\Form\CommandeType;

use App\Form\CommandeTypef;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
 

    #[Route('/s', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }
    #[Route('/f', name: 'app_commande_indexf', methods: ['GET'])]
    public function indexf(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/indexf.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }
    #[Route('/newf', name: 'app_commande_newf', methods: ['GET', 'POST'])]
public function newf(Request $request, EntityManagerInterface $entityManager): Response
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
    $commande = new Commande();
    $commande->setEtatCmd('En cours');
    $commande->setUser($user);
    $form = $this->createForm(CommandeTypef::class, $commande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($commande);
        $entityManager->flush();

        return $this->redirectToRoute('app_commande_indexf', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('commande/newf.html.twig', [
        'commande' => $commande,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
    #[Route('/f/{id}', name: 'app_commande_showf', methods: ['GET'])]
    public function showf(Commande $commande): Response
    {
        return $this->render('commande/showf.html.twig', [
            'commande' => $commande,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }
    #[Route('/f/{id}/edit', name: 'app_commande_editf', methods: ['GET', 'POST'])]
    public function editf(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(CommandeTypef::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/editf.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/f/DEL/{id}', name: 'app_commande_deletef', methods: ['POST'])]
    public function deletef(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_indexf', [], Response::HTTP_SEE_OTHER);
    }
}
