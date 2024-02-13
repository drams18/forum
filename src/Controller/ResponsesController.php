<?php

namespace App\Controller;

use App\Entity\Responses;
use App\Form\ResponseFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ResponsesController extends AbstractController
{
    #[Route('/basket/story', name: 'app_basket_story')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $response = new Responses();
        $form = $this->createForm(ResponseFormType::class, $response);

        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur actuellement connecté
            $user = $this->getUser();

            // Définir l'utilisateur et la date de création de la réponse
            $response->setUser($user->getUsername());
            $response->setCreatedAt(new \DateTimeImmutable());

            // Enregistrer les données dans la base de données
            $entityManager->persist($response);
            $entityManager->flush();

            // Redirection vers une autre page ou affichage d'un message de succès
            $this->addFlash('success', 'Votre réponse a bien été prise en compte.');
        }

        return $this->render('subject/basket/story.html.twig', [
            'responseForm' => $form->createView(),
        ]);  
    }
}