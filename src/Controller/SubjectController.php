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

    #[Route('/subject', name: 'app_subject')]
    public function subject(EntityManagerInterface $entityManager): Response
    {

        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            // Redirection vers la page de connexion ou affichage d'un message d'erreur
            $this->addFlash('error', 'Vous devez être connecté pour accéder au forum.');
            return $this->redirectToRoute('app_login'); // Adapter en fonction de votre configuration
        }

        // Vérifiez s'il existe déjà des sujets enregistrés dans la base de données
        $subjectRepository = $entityManager->getRepository(Subject::class);
        $existingSubjects = $subjectRepository->findAll();

        // Si des sujets existent déjà, affichez-les à l'utilisateur
        if (!empty($existingSubjects)) {
            return $this->render('subject/index.html.twig', [
                'subjects' => $existingSubjects,
            ]);
        }

        // Créez un nouveau sujet uniquement s'il n'existe pas déjà
        $subject = new Subject();
        $subject->setName('Movies');
        $subject->setDescription('Œuvre cinématographique enregistrée sur film (cinéma).');

        // Enregistrez le nouveau sujet dans la base de données
        $entityManager->persist($subject);
        $entityManager->flush();

        // Redirigez l'utilisateur vers une autre page après la création du sujet
        // Vous pouvez spécifier une route valide ici
        return $this->redirectToRoute('app_subject');
    }
}
