<?php

namespace App\Controller;

use App\Entity\SuiviActivité;
use App\Form\SuiviActivitéType;
use App\Repository\SuiviActivitéRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/suivi/activit/')]
class SuiviActivitéController extends AbstractController
{
    #[Route('/', name: 'app_suivi_activit__index', methods: ['GET'])]
    public function index(SuiviActivitéRepository $suiviActivitéRepository): Response
    {
        return $this->render('suivi_activité/index.html.twig', [
            'suivi_activit_s' => $suiviActivitéRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_suivi_activit__new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suiviActivité = new SuiviActivité();
        $form = $this->createForm(SuiviActivitéType::class, $suiviActivité);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suiviActivité);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_activit__index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_activité/new.html.twig', [
            'suivi_activit_' => $suiviActivité,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_activit__show', methods: ['GET'])]
    public function show(SuiviActivité $suiviActivité): Response
    {
        return $this->render('suivi_activité/show.html.twig', [
            'suivi_activit_' => $suiviActivité,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suivi_activit__edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviActivité $suiviActivité, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviActivitéType::class, $suiviActivité);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_activit__index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_activité/edit.html.twig', [
            'suivi_activit_' => $suiviActivité,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_activit__delete', methods: ['POST'])]
    public function delete(Request $request, SuiviActivité $suiviActivité, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suiviActivité->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suiviActivité);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_activit__index', [], Response::HTTP_SEE_OTHER);
    }
}
