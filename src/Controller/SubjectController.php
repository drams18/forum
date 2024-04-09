<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubjectController extends AbstractController
{

    // #[Route('/subject', name: 'app_subject')]
    // public function subject(EntityManagerInterface $entityManager): Response
    // {

    //     $user = $this->getUser();
    //     if (!$user) {
    //         $this->addFlash('error', 'Vous devez être connecté pour accéder au forum.');
    //         return $this->redirectToRoute('app_login'); 
    //     }

    //     $subjectRepository = $entityManager->getRepository(Subject::class);
    //     $existingSubjects = $subjectRepository->findAll();

    //     if (!empty($existingSubjects)) {
    //         return $this->render('subject/index.html.twig', [
    //             'subjects' => $existingSubjects,
    //         ]);
    //     }

    //     $subject = new Subject();
    //     $subject->setName('Movies');
    //     $subject->setDescription('Œuvre cinématographique enregistrée sur film (cinéma).');

    //     $entityManager->persist($subject);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('app_subject');
    // }
}
