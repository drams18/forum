<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Form\BasketSubjectCreationType;
use App\Repository\BasketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubjectCreationController extends AbstractController
{
    private $entityManager;
    private $basketRepository;

    public function __construct(EntityManagerInterface $entityManager, BasketRepository $basketRepository)
    {
        $this->entityManager = $entityManager;
        $this->basketRepository = $basketRepository;
    }

    #[Route('/subject/new/basket', name: 'subject_new_basket')]
    public function newBasket(Request $request): Response
    {
        $subject = new Basket();
        $form = $this->createForm(BasketSubjectCreationType::class, $subject);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($subject);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le sujet a été créé avec succès.');

            return $this->redirectToRoute('app_basket_main');
        }
        // $basketRepository = $entityManager->getRepository(Basket::class);
        $basketSubjects = $this->basketRepository->findBy(['id' => 54]);
        return $this->render('subject_creation/new.html.twig', [
            'form' => $form->createView(),
            'basketSubjects' => $basketSubjects,
        ]);
    }

    #[Route('/subject/basket', name: 'subject_basket')]
    public function add(Request $request): Response{
        $basketSubjects = $this->basketRepository->findBy(['id' => 54]);

        return $this->render('subject/basket/index.html.twig', [

            'basketSubjects' => $basketSubjects
        ]);
    }
}
