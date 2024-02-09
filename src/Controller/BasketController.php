<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket_main')]
    public function basket(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les paniers depuis la base de données
        $basketRepository = $entityManager->getRepository(Basket::class);
        $existingBaskets = $basketRepository->findAll();

        // Récupérer un sujet depuis la base de données
        $subjectRepository = $entityManager->getRepository(Subject::class);
        $subject = $subjectRepository->findOneBy([]); // Vous pouvez utiliser findOneBy avec des conditions appropriées

        // S'il existe déjà des paniers, retourner la liste des paniers existants avec le sujet
        if (!empty($existingBaskets)) {
            return $this->render('subject/basket/index.html.twig', [
                'baskets' => $existingBaskets,
                'subject' => $subject, // Ajouter le sujet aux données rendues
            ]);
        }

        // Si aucun panier n'existe, créer un nouveau panier
        $basket = new Basket();
        $basket->setName('NBA');
        $basket->setDescription('National Basket-Ball League.');

        // Enregistrer le nouveau panier dans la base de données
        $entityManager->persist($basket);
        $entityManager->flush();

        // Retourner la vue avec le nouveau panier et le sujet
        return $this->render('subject/basket/index.html.twig', [
            'baskets' => [$basket], // Mettre le panier dans un tableau pour maintenir la structure
            'subject' => $subject, // Ajouter le sujet aux données rendues
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
    public function story(EntityManagerInterface $entityManager): Response
    {
        // Récupérez le panier de LeBron James depuis la base de données
        $storyBasket = $entityManager->getRepository(Basket::class)->findOneBy(['name' => 'NBA Story']);

        // Vérifiez si le panier de LeBron James existe
        if (!$storyBasket) {
            throw $this->createNotFoundException('NBA Story basket not found');
        }

        // Rendre le template Twig pour afficher les détails du panier de LeBron James
        return $this->render('subject/basket/story.html.twig', [
            'story' => $storyBasket,
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