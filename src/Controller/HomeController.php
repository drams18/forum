<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        if (!$this->security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }

        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        return $this->render('home/index.html.twig', [
            'subjects' => $subjects,
            'posts' => $posts,
        ]);
    }

    public function header(): Response
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();

        return $this->render('components/Header.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}
