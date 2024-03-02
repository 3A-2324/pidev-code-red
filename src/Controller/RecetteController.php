<?php

namespace App\Controller;

use App\Entity\Recette;
use GuzzleHttp\Client;
use App\Controller\Exception;
use App\Form\Recette1Type;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    #[Route('/', name: 'app_recette_index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository,IngredientRepository $ingredientRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
            'allIngredients' => $ingredientRepository->findAll(),
        ]);
    }



    #[Route('/front', name: 'app_recette_indexf', methods: ['GET'])]
public function frontindex(Request $request, PaginatorInterface $paginator, RecetteRepository $recetteRepository, IngredientRepository $ingredientRepository): Response
{
    $data = $recetteRepository->findAll();
    $recettes=$paginator->paginate(
        $data,
        $request->query->getInt('page',1),
        6
    );
    
    $suggestedRecipes = $recetteRepository->findTopSuggestedRecipesForYear(new \DateTime('first day of January this year'), new \DateTime('last day of December this year'));
    $ingredients = $ingredientRepository->findAllIngredients(); // Fetch all ingredients
    
    return $this->render('recette/recetteFront.html.twig', [
        'recettes' => $recettes,
        'suggested_recipes' => $suggestedRecipes,
        'ingredients' => $ingredients, // Pass all ingredients to the template
    ]);
}







#[Route('/filter', name: 'app_recette_filter', methods: ['GET'])]
public function filterRecipes(Request $request, RecetteRepository $recetteRepository): Response
{
    // Get selected ingredient IDs from the request
    $selectedIngredientIds = $request->request->get('ingredients[]', []);

    // Ensure $selectedIngredientIds is an array
    if (!is_array($selectedIngredientIds)) {
        // Handle cases where $selectedIngredientIds is not an array (e.g., single value)
        $selectedIngredientIds = [$selectedIngredientIds];
    }

    // Filter recipes based on selected ingredients
    $recettes = $selectedIngredientIds ? $recetteRepository->findByIngredients($selectedIngredientIds) : [];

    return $this->render('recette/filtered_recipes.html.twig', [
        'recettes' => $recettes,
    ]);
}


    

    
    private function getTopSuggestedRecipesForMonth(RecetteRepository $recetteRepository): array
    {
        $currentDate = new \DateTime();
        $startOfMonth = (clone $currentDate)->modify('first day of this month');
        $endOfMonth = (clone $currentDate)->modify('last day of this month');
    
        return $recetteRepository->findTopSuggestedRecipesForMonth($startOfMonth, $endOfMonth);
    }
    


    #[Route('/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,IngredientRepository $ingredientRepository): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
       $ingredients = $ingredientRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where your images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if something happens during the file upload
                }

                // Update the 'image' property to store the file name instead of its contents
                $recette->setImage($newFilename);
            }

            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
            'ingredients' => $ingredients,
        ]);
    }


    #[Route('/{id}', name: 'app_recette_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }
    #[Route('front/{id}', name: 'app_recette_showfront', methods: ['GET'])]
    public function showf(Recette $recette): Response
    {
        return $this->render('recette/showFront.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where your images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if something happens during the file upload
                }

                // Update the 'image' property to store the file name instead of its contents
                $recette->setImage($newFilename);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recette->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/season', name: 'app_recette_season', methods: ['GET', 'POST'])]
    public function listRecettesWithSeasonalIngredients(): JsonResponse
{
    try {
        // Create a Guzzle HTTP client
        $client = new Client();

        // Make a GET request to the Seasonal Food Guide API endpoint to fetch seasonal ingredients
        $response = $client->request('GET', 'http://linkdata.org/api/1/rdf1s2505i/datapackage.json');
        $seasonalIngredients = json_decode($response->getBody(), true);

        // Dump the decoded JSON data
        dd($seasonalIngredients);
        
        // Alternatively, return a JSON response
        // return new JsonResponse($seasonalIngredients);
    } catch (\Exception $e) {
        // Handle exceptions
        // Log the error
        // Return an error response to the client
        return new Response('Error fetching seasonal ingredients: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
   

}
}
