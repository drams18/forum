<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Basket;
use App\Entity\Subject;
use App\Form\AnswerFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\Query\Expr;

class BasketController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/basket', name: 'app_basket_main')]
    public function basket(EntityManagerInterface $entityManager): Response
    {
        $basketRepository = $entityManager->getRepository(Basket::class);

        $queryBuilder = $basketRepository->createQueryBuilder('b');
        $queryBuilder->where('b.id >= :id')
            ->setParameter('id', 54)
            ->orderBy('b.id', 'ASC');
        $baskets = $queryBuilder->getQuery()->getResult();

        $subjectRepository = $entityManager->getRepository(Subject::class);
        $subject = $subjectRepository->findOneBy([]); // Vous pouvez utiliser findOneBy avec des conditions appropriées

        return $this->render('subject/basket/index.html.twig', [
            'baskets' => $baskets,
            'subject' => $subject,
        ]);
    }


    #[Route('/basket/lebron', name: 'app_basket_lebron')]
    public function lebron(EntityManagerInterface $entityManager): Response
    {

        $lebronBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'LeBron James']);

        if (!$lebronBasket) {
            throw $this->createNotFoundException('LeBron James basket not found');
        }

        return $this->render('subject/basket/lebron.html.twig', [
            'lebron' => $lebronBasket,
        ]);
    }

    #[Route('/basket/story', name: 'app_basket_story')]
    public function story(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, UserRepository $userRepository): Response
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

            $entityManager->persist($answer);
            $entityManager->flush();

            $users = $userRepository->findAll();

            foreach ($users as $user) {
                $this->sendEmail($user->getEmail(), $mailer);
            }

            $this->addFlash('success', 'Réponse enregitrée !');

            return $this->redirectToRoute('app_basket_story');
        }

        $storyBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'NBA Story']);
        $answers = $entityManager->getRepository(Answer::class)->findAll();

        if (!$storyBasket) {
            throw $this->createNotFoundException('NBA Story basket not found');
        }

        return $this->render('subject/basket/story.html.twig', [
            'story' => $storyBasket,
            'form' => $form->createView(),
            'answers' => $answers,
        ]);
    }

    private function sendEmail(String $email, MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from('forum@form.com')
            ->to($email)
            ->subject('Nouveau commentaire ajouté.')
            ->html('<p>Une nouvelle réponse a été ajoutée à votre formulaire.</p>');

        $mailer->send($email);
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
    // #[Route('/basketcreate', name: 'app_basket')]
    // public function create(EntityManagerInterface $entityManager): Response
    // {
    //     $basket = new Basket();
    //     $basket->setName('Rookies');
    //     $basket->setDescription('Un Rookie est une nouvelle recrue en première année dans une des équipes de la NBA.');

    //     // tell Doctrine you want to (eventually) save the Basket (no queries yet)
    //     $entityManager->persist($basket);

    //     // actually execute the queries (i.e. the INSERT query)
    //     $entityManager->flush();

    //     return $this->render('subject/basket/index.html.twig', [
    //         'basket' => $basket,
    //     ]);
    // }
}
