<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('templates_back/index.html.twig', [
            'controller_name' => 'HomeController',
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_homef')]
    public function indexf(): Response
    {
        return $this->render('templates_front/baseFront.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/test', name: 'app_test')]
    public function test(): JsonResponse
{try {
    // Create a Guzzle HTTP client
    $client = new Client();

    // Make a GET request to the Seasonal Food Guide API endpoint to fetch seasonal ingredients
    $response = $client->request('GET', 'http://linkdata.org/api/1/rdf1s2505i/datapackage.json');
    $data = json_decode($response->getBody(), true);
    
    // Check if the 'results' key exists in the response
    if (!isset($data['results'])) {
        throw new \Exception('Response does not contain the expected structure');
    }

    // Extract ingredient names from the response
    $ingredients = [];
    foreach ($data['results']['bindings'] as $item) {
        $ingredient = $item['ingredient']['value'];
        // Extract only the ingredient name from the URL
        $ingredientName = basename($ingredient);
        $ingredients[] = $ingredientName;
    }

    // Return a JSON response with the list of ingredient names
    return new JsonResponse($ingredients);
} catch (GuzzleException $e) {
    // Handle Guzzle HTTP client exceptions
    // Log the error
    // Return an error response to the client
    return new JsonResponse(['error' => 'Error fetching seasonal ingredients: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
} catch (\Exception $e) {
    // Handle other exceptions
    // Log the error
    // Return an error response to the client
    return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
}
}





   
    
}
