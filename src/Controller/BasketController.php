<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Basket;
use App\Entity\Subject;
use App\Form\AnswerFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MailerService $mailerService;

    public function __construct(EntityManagerInterface $entityManager, MailerService $mailerService)
    {
        $this->entityManager = $entityManager;
        $this->mailerService = $mailerService;
    }

    #[Route('/basket', name: 'app_basket_main')]
    public function basket(): Response
    {
        $subject = $this->entityManager->getRepository(Subject::class)->findAll();
        
        $startId = 63; // L'ID à partir duquel vous souhaitez récupérer les sujets
        $baskets = $this->entityManager->getRepository(Basket::class)->findAll();
    
        // Filtrer les sujets à partir de l'ID 63
        $filteredBaskets = array_filter($baskets, function($basket) use ($startId) {
            return $basket->getId() >= $startId;
        });

        return $this->render('subject/basket/index.html.twig', [
            'baskets' => $filteredBaskets,
            'subject' => $subject,
        ]);
    }

    #[Route('/basket/story', name: 'app_basket_story')]
    public function story(Request $request, UserRepository $userRepository): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerFormType::class, $answer);

        $form->handleRequest($request);

        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour répondre.');
            return $this->redirectToRoute('app_login');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setUser($user);
            $answer->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($answer);
            $this->entityManager->flush();

            $startID = 63;
            $usersAnswered = $userRepository->find

            // $users = $userRepository->findAll();

            foreach ($users as $userItem) {
                $this->mailerService->sendEmail($userItem->getEmail(), 'Nouveau commentaire ajouté.', 'Une nouvelle réponse a été ajoutée à votre formulaire.');
            }

            $this->addFlash('success', 'Réponse enregistrée !');

            return $this->redirectToRoute('app_basket_story');
        }

        $storyBasket = $this->entityManager->getRepository(Basket::class)->findOneBy(['name' => 'NBA Story']);
        $answers = $this->entityManager->getRepository(Answer::class)->findAll();

        if (!$storyBasket) {
            throw $this->createNotFoundException('NBA Story basket inexistant.');
        }

        return $this->render('subject/basket/story.html.twig', [
            'story' => $storyBasket,
            'form' => $form->createView(),
            'answers' => $answers,
        ]);
    }

    #[Route('/basket/mvp', name: 'app_basket_mvp')]
    public function mvp(EntityManagerInterface $entityManager): Response
    {
        $mvpBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'MVP']);

        if (!$mvpBasket) {
            throw $this->createNotFoundException('MVP basket not found');
        }

        return $this->render('subject/basket/mvp.html.twig', [
            'mvp' => $mvpBasket,
        ]);
    }


    #[Route('/basket/awards', name: 'app_basket_awards')]
    public function awards(EntityManagerInterface $entityManager): Response
    {
        $awardsBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'NBA Awards']);

        if (!$awardsBasket) {
            throw $this->createNotFoundException('NBA Awards not found');
        }

        return $this->render('subject/basket/awards.html.twig', [
            'awards' => $awardsBasket,
        ]);
    }

    #[Route('/basket/rookies', name: 'app_basket_rookies')]
    public function rookies(EntityManagerInterface $entityManager): Response
    {
        $rookiesBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'Rookies']);

        if (!$rookiesBasket) {
            throw $this->createNotFoundException('Rookies not found');
        }

        return $this->render('subject/basket/rookies.html.twig', [
            'rookies' => $rookiesBasket,
        ]);
    }

    #[Route('/basket/subject/{id}', name: 'app_basket_subject')]
    public function subject($id): Response
    {
        $basket = $this->entityManager->getRepository(Basket::class)->find($id);

        if (!$basket) {
            throw $this->createNotFoundException('Le sujet de panier demandé n\'existe pas.');
        }

        return $this->render('subject/basket/news.html.twig', [
            'basket' => $basket,
        ]);
    }
}
