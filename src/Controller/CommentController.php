<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/comment', name: 'app_comment')]
    public function comment(): Response
    {
        $comments = $this->entityManager->getRepository(Comment::class)->findAll();
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();
        
        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
            'subjects' => $subjects,
        ]);
    }
}
