<?php

namespace App\Controller;

use App\Entity\Answer;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnswerController extends AbstractController
{

    public function new(Request $request): Response
    {
        // creates a task object and initializes some data for this example
        $task = new Answer();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTimeImmutable('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();

        return $this->render('subject/basket/story.html.twig', [
            'controller_name' => 'AnswerController',
        ]);
    }

    // #[Route('/answer', name: 'app_answer')]
    // public function index(): Response
    // {
    //     return $this->render('answer/index.html.twig', [
    //         'controller_name' => 'AnswerController',
    //     ]);
    // }
}
