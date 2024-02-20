<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Basket;
use App\Entity\Subject;
use App\Form\AnswerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        // Récupérer tous les paniers depuis la base de données
        $basketRepository = $entityManager->getRepository(Basket::class);
        
        // Récupérer tous les paniers à partir de l'ID 54
        $queryBuilder = $basketRepository->createQueryBuilder('b');
        $queryBuilder->where('b.id >= :id')
                     ->setParameter('id', 54)
                     ->orderBy('b.id', 'ASC');
        $baskets = $queryBuilder->getQuery()->getResult();
    
        // Récupérer un sujet depuis la base de données
        $subjectRepository = $entityManager->getRepository(Subject::class);
        $subject = $subjectRepository->findOneBy([]); // Vous pouvez utiliser findOneBy avec des conditions appropriées
    
        // Retourner la vue avec les paniers et le sujet
        return $this->render('subject/basket/index.html.twig', [
            'baskets' => $baskets,
            'subject' => $subject,
        ]);
    }       


    #[Route('/basket/lebron', name: 'app_basket_lebron')]
    public function lebron(EntityManagerInterface $entityManager): Response
    {

        // Récupérez le panier de LeBron James depuis la base de données
        $lebronBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'LeBron James']);

        // Vérifiez si le panier de LeBron James existe
        if (!$lebronBasket) {
            throw $this->createNotFoundException('LeBron James basket not found');
        }

        // Rendre le template Twig pour afficher les détails du panier de LeBron James
        return $this->render('subject/basket/lebron.html.twig', [
            'lebron' => $lebronBasket,
        ]);
    }

    #[Route('/basket/story', name: 'app_basket_story')]
    public function story(Request $request, EntityManagerInterface $entityManager): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerFormType::class, $answer);

        $form->handleRequest($request);

        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            // Redirection vers la page de connexion ou affichage d'un message d'erreur
            $this->addFlash('error', 'Vous devez être connecté pour répondre.');
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Définir l'utilisateur et la date de création de la réponse
            $answer->setUser($user);
            $answer->setCreatedAt(new \DateTimeImmutable());

            // Enregistrer les données dans la base de données
            $entityManager->persist($answer);
            $entityManager->flush();

            // Afficher un message de succès
            $this->addFlash('success', 'Votre réponse a bien été enregistrée.');

            return $this->redirectToRoute('app_basket_story');
        }

        // Récupérez le panier de LeBron James depuis la base de données
        $storyBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'NBA Story']);
        $answers = $entityManager->getRepository(Answer::class)->findAll();

        // Vérifiez si le panier de LeBron James existe
        if (!$storyBasket) {
            throw $this->createNotFoundException('NBA Story basket not found');
        }

        // Rendre le template Twig pour afficher les détails du panier de LeBron James
        return $this->render('subject/basket/story.html.twig', [
            'story' => $storyBasket,
            'form' => $form->createView(),
            'answers' => $answers,
        ]);
    }


    #[Route('/basket/mvp', name: 'app_basket_mvp')]
    public function mvp(EntityManagerInterface $entityManager): Response
    {
        // Récupérez le panier de LeBron James depuis la base de données
        $mvpBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'MVP']);

        // Vérifiez si le panier de LeBron James existe
        if (!$mvpBasket) {
            throw $this->createNotFoundException('MVP basket not found');
        }

        // Rendre le template Twig pour afficher les détails du panier de LeBron James
        return $this->render('subject/basket/mvp.html.twig', [
            'mvp' => $mvpBasket,
        ]);
    }


    #[Route('/basket/awards', name: 'app_basket_awards')]
    public function awards(EntityManagerInterface $entityManager): Response
    {
        // Récupérez le panier de LeBron James depuis la base de données
        $awardsBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'NBA Awards']);

        // Vérifiez si le panier de LeBron James existe
        if (!$awardsBasket) {
            throw $this->createNotFoundException('NBA Awards not found');
        }

        // Rendre le template Twig pour afficher les détails du panier de LeBron James
        return $this->render('subject/basket/awards.html.twig', [
            'awards' => $awardsBasket,
        ]);
    }

    #[Route('/basket/rookies', name: 'app_basket_rookies')]
    public function rookies(EntityManagerInterface $entityManager): Response
    {
        // Récupérez le panier de LeBron James depuis la base de données
        $rookiesBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'Rookies']);

        // Vérifiez si le panier de LeBron James existe
        if (!$rookiesBasket) {
            throw $this->createNotFoundException('Rookies not found');
        }

        // Rendre le template Twig pour afficher les détails du panier de LeBron James
        return $this->render('subject/basket/rookies.html.twig', [
            'rookies' => $rookiesBasket,
        ]);
    }

    #[Route('/basket/subject/{id}', name: 'app_basket_subject')]
    public function subject($id): Response
    {
        // Récupérer le sujet de panier avec l'ID fourni depuis la base de données
        $basket = $this->entityManager->getRepository(Basket::class)->find($id);

        // Vérifier si le sujet de panier existe
        if (!$basket) {
            throw $this->createNotFoundException('Le sujet de panier demandé n\'existe pas.');
        }

        // Rendre la vue pour afficher les détails du sujet de panier
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
