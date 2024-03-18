<?php

namespace App\Controller;

use App\Entity\Basket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MySubjectController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/my/subject', name: 'app_my_subject')]
    public function mysubject(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login'); 
        }

        $mySubjects = $user->getBaskets();

        return $this->render('my_subject/index.html.twig', [
            'mySubjects' => $mySubjects,
            'user' => $user,
        ]);
    }

    #[Route('/my/subject/delete/{id}', name: 'app_my_subject_delete')]
    public function deleteSubject(Request $request, int $id): Response
    {
        $subject = $this->entityManager->getRepository(Basket::class)->find($id);

        // Vérifier si le sujet existe
        if (!$subject) {
            throw $this->createNotFoundException('Le sujet n\'existe pas.');
        }

        // Vérifier si l'utilisateur actuel est l'auteur du sujet
        if ($subject->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce sujet.');
        }

        // Supprimer le sujet
        $this->entityManager->remove($subject);
        $this->entityManager->flush();

        // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
        return $this->redirectToRoute('app_my_subject');
    }
}
