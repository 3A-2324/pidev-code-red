<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{ //back
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

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_user');
        }

        return $this->render('user/updateUser.html.twig', ['f' => $form->createView()]);
    }
    //recherche
    
     
    #[Route('/back', name: 'back_user')]
    public function admin(): Response
    {
        return $this->render('user/back.html.twig');
    }
    #[Route('/front', name: 'front_user')]
    public function front(): Response
    {
        return $this->render('user/front.html.twig');
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
        $plaintextPassword = $userData->getPassword(); 
        //$user->setToken($this->generateToken());
        $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash( type :"success", message:"inscription réussie !");

    return $this->redirectToRoute('front_user');
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

        return $this->redirectToRoute('user_show');
    }

    return $this->render('user/updateUser.html.twig', ['f' => $form->createView()]);
}
    #[Route('/home', name: 'home_user')]
    public function home(): Response
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render('user/front.html.twig', [
            'u' => $users
        ]);
    }
    #[Route('/showuser/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/showf.html.twig',[
        'user' => $user, ]);
            
    }
}