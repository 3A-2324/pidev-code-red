<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;

use App\Form\ProduitSearchType;
use App\Form\TrierProduitsType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use App\Entity\User;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/produit')]
class ProduitController extends AbstractController
{


    #[Route('/produit/{id}/qr_bundle', name: 'app_produit_qr_bundle', methods: ['GET'])]
    public function qrBundle(int $id, ProduitRepository $produitRepository): Response
    {
        // Find the product by its ID
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('The product does not exist');
        }

        // Data to be encoded in the QR code
        $data = json_encode([
            'id' => $produit->getId(),
            'name' => $produit->getNomProduit(),
            'description' => $produit->getDescription(),
            'price' => $produit->getPrix(),
            // You can add more fields here as needed
        ]);

        // Generate QR code
        $qrCode = QrCode::create($data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel('high') // Use the string value directly
            ->setSize(400)
            ->setMargin(10);

        // Render the QR code image
        $qrCodeImage = $qrCode->writeDataUri();

        // Create a response with the QR code image
        $response = new Response($qrCodeImage);

        return $response;
    }
#[Route('/', name: 'app_produit_index', methods: ['GET'])]
public function index(ProduitRepository $produitRepository): Response
{
    $produits = $produitRepository->findAll();

    return $this->render('produit/index.html.twig', [
        'produits' => $produits,
    ]);
}
   #[Route('/f', name: 'app_produit_indexf', methods: ['GET'])]
public function indexFiltered(Request $request, ProduitRepository $produitRepository): Response
{
    // Créer le formulaire de filtrage des prix
    $form = $this->createForm(ProduitSearchType::class);
    $form->handleRequest($request);

    // Récupérer le terme de recherche depuis la requête
    $searchTerm = $request->query->get('q');

    // Initialiser la variable $produits
    $produits = [];

    // Si le formulaire est soumis et valide, filtrer les produits par prix
    if ($form->isSubmitted() && $form->isValid()) {
        $formData = $form->getData();
        $prix = $formData['prix']; // Utilisez la clé 'prix' pour récupérer le prix filtré
        $produits = $produitRepository->findByPrice($prix);
    } else {
        // Si le formulaire n'est pas soumis ou n'est pas valide, rechercher les produits correspondants au terme
        $produits = $produitRepository->findBySearchTerm($searchTerm);
    }

    // Rendre le template avec les produits filtrés ou non
    return $this->render('produit/indexf.html.twig', [
        'produits' => $produits,
        'form' => $form->createView(),
    ]);  
}
#[Route('/sorted', name: 'app_produit_sorted', methods: ['GET'])]
public function sortedIndex(Request $request, ProduitRepository $produitRepository): Response
{
    
    $form = $this->createForm(TrierProduitsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tri = $form->get('tri')->getData();
            $produits = $produitRepository->findBySortedNom($tri);

        } else {
            // Si le formulaire n'est pas soumis, affichez les produits sans tri
            $produits = $produitRepository->findAll();
        }

        return $this->render('produit/indexf.html.twig', [
            'form' => $form->createView(),
            'produits' => $produits,
        ]);
        
}


    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, MailerInterface $mailer): Response
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
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where your images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('img_directory'), // specify the directory where images should be stored
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if something happens during the file upload
                }

                // Update the 'image' property to store the file name instead of its contents
                $produit->setImage($newFilename);
            }
            $entityManager->persist($produit);
            $entityManager->flush();

            // Send email notification
            $email = (new Email())
                ->from('kharrat.raed@esprit.tn')
                ->to('aziz.limemm1@gmail.com')
                ->subject('New Product Added')
                ->text('A new product has been added!')
                ->html('<p>A new product has been added to the system.</p>');

            $mailer->send($email);


            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('frr/{id}', name: 'app_produit_showf', methods: ['GET'])]
    public function showf(Produit $produit): Response
    {
        return $this->render('produit/showf.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('img_directory'), // specify the directory where images should be stored
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if something happens during the file upload
                }

                // Update the 'image' property to store the file name instead of its contents
                $produit->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    
}

