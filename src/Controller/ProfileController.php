<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function profilePage(): Response
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();

        return $this->render('profile/index.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    #[Route('/profil/edit', name: 'app_profile_edit')]
    public function profileEdit(Request $request): Response
    {
        $user = $this->getUser();
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();

        $form = $this->createForm(UserProfileFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('app_profile');
        }
    
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'subjects' => $subjects,
        ]);
    }
}
