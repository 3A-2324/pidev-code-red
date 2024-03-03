<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\SuiviActivite;
use App\Entity\User;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activite')]
class ActiviteController extends AbstractController
{
    #[Route('/', name: 'app_activite_index', methods: ['GET'])]
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('/list', name: 'app_activite_list', methods: ['GET'])]
    public function listActivite(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/listActiviteFront.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('activite/{id}', name: 'app_activite_add_suivi', methods: ['GET'])]
    public function showActiviteFront(ActiviteRepository $activiteRepository,$id): Response
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
        $suiviActivite->setActivite($this->getDoctrine()->getManager()->getRepository(Activite::class)->find($id));
        $suiviActivite->setUser($user);
        $suiviActivite->setRep(0);
        $suiviActivite->setDate(new \DateTime());
        return $this->render('suivi_activite/listFront.html.twig', [
            'suivi_activit_s' => $this->getDoctrine()->getManager()->getRepository(SuiviActivite::class)->findBy(['user'=>$user])
        ]);
    }





    #[Route('/new', name: 'app_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('kernel.project_dir') . '/public/img', $fileName);
                $activite->setImage($fileName);
            }

            $videoFile = $form['video']->getData();
            if ($videoFile) {
                $videoFileName = md5(uniqid()) . '.' . $videoFile->guessExtension();
                $videoFile->move($this->getParameter('kernel.project_dir') . '/public/vid', $videoFileName);
                $activite->setVideo($videoFileName);
            }


            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
    }
}