<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Subject;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/comment', name: 'app_comment')]
    public function allComments(): Response
    {
        $comments = $this->entityManager->getRepository(Comment::class)->findAll();
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();

        return $this->render('comment/dashboard.html.twig', [
            'comments' => $comments,
            'subjects' => $subjects,
        ]);
    }
    #[Route('/comment/add/{postId}', name: 'app_comment_add', methods: ['POST'])]
    public function comment(Request $request, int $postId): Response
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();
        $post = $this->entityManager->getRepository(Post::class)->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }
    
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($this->getUser());
    
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('app_post_details', ['id' => $postId]);
        }
    
        $comments = $post->getComments();
    
        return $this->render('comment/index.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
            'subjects' => $subjects,
            'comments' => $comments,
        ]);
    }

    #[Route('/comment-delete/{id}', name: 'app_comment_delete')]
    public function deleteComments(Comment $comment): RedirectResponse
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_comment');
    }

    #[Route('/comment-edit/{id}', name: 'app_comment_edit')]
    public function editComment(Request $request, int $id): Response
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);
        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }
    
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
    
            return $this->redirectToRoute('app_comment');
        }
    
        return $this->render('comment/edit.html.twig', [
            'form' => $form->createView(),
            'subjects' => $subjects,
        ]);
    }
}
