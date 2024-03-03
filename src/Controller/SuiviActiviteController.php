<?php

namespace App\Controller;

use App\Entity\SuiviActivite;
use App\Entity\User;
use App\Form\SuiviActiviteType;
use App\Repository\SuiviActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/suivi/activit')]
class SuiviActiviteController extends AbstractController
{

    #[Route('/newFront', name: 'app_suivi_activit__new_front', methods: ['GET', 'POST'])]
    public function newFront(Request $request, EntityManagerInterface $entityManager): Response
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
        $suiviActivite = new SuiviActivite();
        $form = $this->createForm(SuiviActiviteType::class, $suiviActivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suiviActivite->setUser($user);
            $entityManager->persist($suiviActivite);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_activit__index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_activite/newFront.html.twig', [
            'suivi_activit_' => $suiviActivite,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_suivi_activit__new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suiviActivite = new SuiviActivite();
        $form = $this->createForm(SuiviActiviteType::class, $suiviActivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suiviActivite);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_activit__index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_activite/new.html.twig', [
            'suivi_activit_' => $suiviActivite,
            'form' => $form,
        ]);
    }




    #[Route('/', name: 'app_suivi_activit__index_front', methods: ['GET'])]
    public function indexFront(SuiviActiviteRepository $suiviActiviteRepository): Response
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
        return $this->render('suivi_activite/listFront.html.twig', [
            'suivi_activit_s' => $suiviActiviteRepository->findBy(['user'=>$user]),
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_activit__show_front', methods: ['GET'])]
    public function showFront(SuiviActivite $suiviActivite): Response
    {
        return $this->render('suivi_activite/showFront.html.twig', [
            'suivi_activit_' => $suiviActivite,
        ]);
    }

    #[Route('/', name: 'app_suivi_activit__index', methods: ['GET'])]
    public function index(SuiviActiviteRepository $suiviActiviteRepository): Response
    {
        return $this->render('suivi_activite/index.html.twig', [
            'suivi_activit_s' => $suiviActiviteRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_activit__show', methods: ['GET'])]
    public function show(SuiviActivite $suiviActivite): Response
    {
        return $this->render('suivi_activite/show.html.twig', [
            'suivi_activit_' => $suiviActivite,
        ]);
    }




    #[Route('/{id}/edit', name: 'app_suivi_activit__edit_front', methods: ['GET', 'POST'])]
    public function editFront(Request $request, SuiviActivite $suiviActivite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviActiviteType::class, $suiviActivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_activit__index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_activite/editFront.html.twig', [
            'suivi_activit_' => $suiviActivite,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_suivi_activit__edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviActivite $suiviActivite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviActiviteType::class, $suiviActivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_activit__index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_activite/edit.html.twig', [
            'suivi_activit_' => $suiviActivite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_activit__delete', methods: ['POST'])]
    public function delete(Request $request, SuiviActivite $suiviActivite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suiviActivite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suiviActivite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_activit__index', [], Response::HTTP_SEE_OTHER);
    }
}