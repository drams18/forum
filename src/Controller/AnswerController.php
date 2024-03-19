<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\User;
use App\Form\AnswerFormType;
use App\Form\AnswerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    #[Route('/add-answer', name: 'add_answer')]
    public function addAnswer(Request $request, MailerInterface $mailer): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerFormType::class, $answer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            $users = $entityManager->getRepository(User::class)->findAll();
            foreach ($users as $user) {
                $this->sendEmail($user, $answer, $mailer);
            }

            $this->addFlash('success', 'Les e-mails ont été envoyés avec succès.');

            return $this->redirectToRoute('app_basket_story');
        }

        return $this->render('answer/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Méthode pour envoyer un e-mail à un utilisateur (exemple)
    private function sendEmail(User $user, Answer $answer, MailerInterface $mailer): void
    {
        // Logique d'envoi d'e-mail
        // ...
    }
}
