<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserType1;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


class UserController extends AbstractController
{ //back

    #[Route('/back', name: 'back_user')]
    public function admin(): Response
    {
        return $this->render('BackPage.html.twig');
    }
    #[Route('/front', name: 'front_user')]
    public function front(): Response
    {
        return $this->render('HomePageS.html.twig');
    }
    #[Route('/user', name: 'display_user')]
    public function index(): Response
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', [
            'u' => $users
        ]);
    }

    #[Route('/ajoutuser', name: 'ajouter_user')]
    public function adduser(Request $request , UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            $user->setRoles(['ROLE_ADMIN']);

            $user->setStatus("Active");
            $plaintextPassword = $userData->getPassword(); 
    
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('display_user');
        }

        return $this->render('user/createUser.html.twig', ['f' => $form->createView()]);
    }

    
    #[Route('/suppuser/{id}', name: 'supp_user')]
   

    public function suppressionuser($id, UserRepository $repository ,ManagerRegistry $doctrine)
    {
        $book = $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($book);
        $em->flush();


        return $this->redirectToRoute('display_user');
    }

    #[Route('/modifuser/{id}', name: 'modif_user')]
    public function modifruser(Request $request, $id): Response
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);

        $form = $this->createForm(UserType1::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_user');
        }

        return $this->render('user/updateUser.html.twig', ['f' => $form->createView()]);
    }
    //recherche
    #[Route('/act/{id}', name: 'act')]

    public function Active($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $newRoles = ['ROLE_USER'];
        $user->setRoles($newRoles);
        $user->setStatus("Active");
        $entityManager->flush();
        return $this->redirectToRoute('display_user');
    }
    #[Route('/block/{id}', name: 'block')]

    public function Block($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $newRoles = ['ROLE_ADMIND'];
        $user->setRoles($newRoles);
        $user->setStatus("Disabled");
        $entityManager->flush();
        return $this->redirectToRoute('display_user');
    }
    #[Route('/TrierParDateDESC', name: 'TrierParDateDESC')]

    public function TrierParDate(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findByNom();

        return $this->render('user/index.html.twig', [
            'u' => $user,
        ]);
    }


//front
#[Route('/ajoutuserf', name: 'ajouterf_user')]
public function ajoutuser(Request $request, UserPasswordHasherInterface $passwordHasher): Response
{
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $userData = $form->getData();

        $user->setRoles(['ROLE_USER']);
        $user->setStatus("Active");
        $plaintextPassword = $userData->getPassword(); 

        $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

    return $this->redirectToRoute('app_login');
    }

    return $this->render('user/createUserf.html.twig', ['f' => $form->createView()]);
}

#[Route('/modifuserf/{id}', name: 'modif_userf')]
public function modifruserf(Request $request, $id): Response
{
    $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('showf_user');
    }

    return $this->render('user/updateUserf.html.twig', ['f' => $form->createView()]);
}
#[Route('/PDF', name: 'PDF')]

public function listl(UserRepository $UserRepository): Response
{

    $pdfOptions = new Options();
    $pdfOptions->set('defaultFront', 'Arial');

    $dompdf = new Dompdf($pdfOptions);
    $user = $UserRepository->findAll();


    $html = $this->renderView('user/pdf.html.twig', [
        'u' => $user,
    ]);

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    $dompdf->stream("mypdf.pdf", [
        "Attachment" => true
    ]);
    return new Response('Response content');
}
#[Route('/home', name: 'home_user')]
    public function home(): Response
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render('HomePage.html.twig', [
            'u' => $users
        ]);
    }
   
    #[Route('profile', name: 'show_user', methods: ['GET'])]
    public function showuser(Request $request): Response
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
        return $this->render('user/profile.html.twig', [
           'user' => $user,

     ]);
    }
    #[Route('profilef', name: 'showf_user', methods: ['GET'])]
    public function showuserf(Request $request): Response
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
        return $this->render('user/profilef.html.twig', [
           'user' => $user,
           
     ]);
    }
}