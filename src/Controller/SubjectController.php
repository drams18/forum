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
    // public function createSubject(EntityManagerInterface $entityManager): Response
    // {
    //     $subject = new Subject();
    //     $subject->setName('Movies');
    //     $subject->setDescription('Œuvre cinématographique enregistrée sur film (cinéma).');

    //     // tell Doctrine you want to (eventually) save the Basket (no queries yet)
    //     $entityManager->persist($subject);

    //     // actually execute the queries (i.e. the INSERT query)
    //     $entityManager->flush();

    //     return $this->render('subject/index.html.twig', [
    //         'subject' => $subject,
    //     ]);
    // }

    #[Route('/subject', name: 'app_subject')]
    public function subject(EntityManagerInterface $entityManager): Response
    {
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



    #[Route('/basket', name: 'create_basket')]
    public function createBasket(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les paniers depuis la base de données
        $basketRepository = $entityManager->getRepository(Basket::class);
        $existingBaskets = $basketRepository->findAll();

        // S'il existe déjà des paniers, retourner la liste des paniers existants
        if (!empty($existingBaskets)) {
            return $this->render('subject/basket/index.html.twig', [
                'basket' => $existingBaskets,
            ]);
        }

        // Si aucun panier n'existe, créer un nouveau panier
        $basket = new Basket();
        $basket->setName('NBA');
        $basket->setDescription('National Basket-Ball League.');

        // Enregistrer le nouveau panier dans la base de données
        $entityManager->persist($basket);
        $entityManager->flush();

        // Retourner la vue avec le nouveau panier
        return $this->render('subject/basket/index.html.twig', [
            'baskets' => [$basket], // Mettre le panier dans un tableau pour maintenir la structure
        ]);
    }


    #[Route('/basket/{id}', name: 'basket_show')]
    public function showBasket(EntityManagerInterface $entityManager, int $id): Response
    {
        $basket = $entityManager->getRepository(Basket::class)->find($id);

        $repository = $entityManager->getRepository(Basket::class);
        $basket = $repository->find($id);

        if (!$basket) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        return $this->render('subject/basket/index.html.twig', [
            'basket' => $basket,
        ]);
    }
}
