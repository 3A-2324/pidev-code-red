<?php

namespace App\Controller;

use App\Entity\Journal;
use App\Form\JournalType;
use App\Repository\JournalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/journal')]
class JournalController extends AbstractController
{
    #[Route('/', name: 'app_journal_index', methods: ['GET', 'POST'])]
    public function index(Request $request, JournalRepository $journalRepository): Response
    {
        // Default to the current date
        $date = new \DateTime();
    
        // If a form has been submitted, use the selected date
        if ($request->isMethod('POST')) {
            $date = new \DateTime($request->request->get('date'));
        }
    
        // Retrieve the list of journals
        $journals = $journalRepository->findAll();
    
        // Calculate the sum of calories for the selected date
        $caloriesSums = $journalRepository->sumCaloriesByDate($date);
    
        // Render the template with the data
        return $this->render('journal/index.html.twig', [
            'journals' => $journals,
            'calories_sums' => $caloriesSums,
            'selected_date' => $date->format('Y-m-d'),
        ]);
    }


    #[Route('/new', name: 'app_journal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, JournalRepository $journalRepository): Response
    {
        $journal = new Journal();
        $form = $this->createForm(JournalType::class, $journal);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $existingJournal = $journalRepository->findOneBy(['Date' => $journal->getDate()]);
    
            if ($existingJournal) {
                // If a journal entry already exists for the selected date,
                // redirect to the edit action for that journal entry
                return $this->redirectToRoute('app_journal_edit', ['id' => $existingJournal->getId()]);
            }
            $journal->setCaloriesJournal($journal->getCaloriesJournal());
            $entityManager->persist($journal);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_journal_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('journal/new.html.twig', [
            'journal' => $journal,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_journal_show', methods: ['GET'])]
    public function show(Journal $journal): Response
    {
        return $this->render('journal/show.html.twig', [
            'journal' => $journal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_journal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Journal $journal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JournalType::class, $journal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_journal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('journal/edit.html.twig', [
            'journal' => $journal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_journal_delete', methods: ['POST'])]
    public function delete(Request $request, Journal $journal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $journal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($journal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_journal_index', [], Response::HTTP_SEE_OTHER);
    }
}
