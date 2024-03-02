<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Journal;
use App\Form\JournalType;
use App\Repository\JournalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\RecetteRepository;
use DateTime;
use Twilio\Rest\Client;


#[Route('/journal')]
class JournalController extends AbstractController
{

    #[Route('/generate-weekly-ingredient-pdf', name: 'generate_weekly_ingredient_pdf')]
    public function generateWeeklyIngredientPdf(EntityManagerInterface $entityManager, JournalRepository $repo): Response
    {
        $currentDate = new DateTime(); // Create a new DateTime object representing the current date and time
        $currentDateString = $currentDate->format('Y-m-d');

        $startOfWeek = DateTime::createFromFormat('Y-m-d', $currentDateString);
        
        $pdfoptions = new Options();
        $pdfoptions->set('defaultFont', 'Arial');
        $pdfoptions->setIsRemoteEnabled(true);
        
    
        $dompdf = new Dompdf($pdfoptions);
    
        $journals = $repo->findJournalsByWeek($startOfWeek);
    
        $html = $this->renderView('pdf/weekly_ingredient_list.html.twig', [
            'journals' => $journals
        ]);
    
        $html = '<div>' . $html . '</div>';
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'landscape');
        $dompdf->render();
    
        $pdfOutput = $dompdf->output();
        
    
        return new Response($pdfOutput, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="journalPDF.pdf"'
        ]);
    }


    #[Route('/', name: 'app_journal_index', methods: ['GET', 'POST'])]
    public function index(Request $request, JournalRepository $journalRepository): Response
    {
   
    
        $journals = $journalRepository->findAll();
    
        $events = [];
    
        foreach ($journals as $journal) {
            $events[] = [
                'id' => $journal->getId(),
                'title' => $journal->getCaloriesJournal(), // Using getCaloriesJournal() method
                'start' => $journal->getDate()->format('Y-m-d'),
                'calories_journal' => $journal->getCaloriesJournal(), // Include calories_journal property
            ];
        }
        
        $data = json_encode($events);
        
        return $this->render('journal/index.html.twig', compact('data'));
    }


    #[Route('/new', name: 'app_journal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, JournalRepository $journalRepository): Response
    {
        // Initialize variables
        $existingJournal = null;
        $journal = new Journal();
        $form = $this->createForm(JournalType::class, $journal);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if a journal with the same date already exists
            $existingJournal = $journalRepository->findOneBy(['Date' => $journal->getDate()]);

        /*//SMS
        $accountSID = "AC52b4c1f2b1f2ecfe3a48c85b038d88d0";
        $authToken = "0774eed704b3f40dff0e9883f11ae248";
        $twilioNumber = "+14698080489";
        // Initialize Twilio client
        $twilio = new Client($accountSID,$authToken);

        // Send SMS
        $message = $twilio->messages->create(
            '+21655845445', // To phone number
            [
                'from' => $twilioNumber, 
                'body' => 'This is a test message from Twilio!'
            ]
        );
        //END SMS*/

            
            if ($existingJournal) {
                // If an existing journal is found, add the new journal entries to it
                foreach ($journal->getRecetteRef() as $recette) {
                    $existingJournal->addRecetteRef($recette);
                }
                // Recalculate the total calories for the existing journal
                $existingJournal->setCaloriesJournal();
                $entityManager->flush(); // Update the existing journal in the database
            } else {
                // If no existing journal is found, persist the new journal
                $journal->setCaloriesJournal();
                $entityManager->persist($journal);
                $entityManager->flush();
            }
    
            return $this->redirectToRoute('app_journal_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('journal/new.html.twig', [
            'journal' => $existingJournal ?: $journal, // Use the existing journal if found, otherwise use a new one
            'form' => $form->createView(), // Create the form view
        ]);
    }
    

    #[Route('/{id}', name: 'app_journal_show', methods: ['GET'])]
    public function show(Journal $journal): Response
    {
        // Retrieve the associated recettes for the given journal
        $recettes = $journal->getRecetteRef();

        // Prepare an array to store recette details
        $recettesData = [];

        // Loop through associated recettes to gather details
        foreach ($recettes as $recette) {
            $recettesData[] = [
                'id' => $recette->getId(),
                'nom' => $recette->getNom(),
                'calorieRecette' => $recette->getCalorieRecette(),
                'Image' => $recette->getImage(),
                // Include other properties of recette as needed
            ];
        }

        // Prepare journal data to be sent as JSON response
        $journalData = [
            'id' => $journal->getId(),
            'date' => $journal->getDate() ? $journal->getDate()->format('Y-m-d') : null,
            'calories_journal' => $journal->getCaloriesJournal(),
            'recettes' => $recettesData, // Include details of associated recettes
        ];

        return $this->render('journal/show.html.twig', [
        'journal' => $journalData,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_journal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Journal $journal, EntityManagerInterface $entityManager, JournalRepository $journalRepository): Response
    {
        $form = $this->createForm(JournalType::class, $journal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingJournal = $journalRepository->findOneBy(['Date' => $journal->getDate()]);

            if ($existingJournal && $existingJournal->getId() !== $journal->getId()) {
                // If a journal entry already exists for the selected date,
                // redirect to the edit action for that journal entry
                return $this->redirectToRoute('app_journal_edit', ['id' => $existingJournal->getId()]);
            }

            $journal->setCaloriesJournal();
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


    private function getTopSuggestedRecipes(RecetteRepository $recetteRepository): array
    {
        $currentDate = new \DateTime();
        $startOfMonth = (clone $currentDate)->modify('first day of this month');
        $endOfMonth = (clone $currentDate)->modify('last day of this month');
    
        return $recetteRepository->findTopSuggestedRecipesForMonth($startOfMonth, $endOfMonth);
    }

    

    
}
