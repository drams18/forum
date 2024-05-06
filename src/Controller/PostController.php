<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Subject;
use App\Form\CommentFormType;
use App\Form\PostFormType;
use App\Twig\ProjectTwigExtension;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController

{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route('/posts', name: 'app_post')]
    public function showAllPosts(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        return $this->render('post/show.html.twig',  [
            'posts' => $posts,
        ]);
    }

    #[Route('/post-add', name: 'app_post_create')]
    public function addPost(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setOwner($this->security->getUser());
            $post->setCreatedAt(new DateTimeImmutable());
            $post->setUpdatedAt(new DateTimeImmutable());

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->addFlash('success', 'Post créé avec succès.');

            return $this->redirectToRoute('app_post');
        }

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}', name: 'app_post_details')]
    public function showPostDetails(Post $post): Response
    {

        return $this->render('post/post.details.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post-delete/{id}', name: 'app_post_delete')]
    public function deletePost(Post $post): RedirectResponse
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->addFlash('success', 'Le post a été supprimé avec succès.');

        return $this->redirectToRoute('app_post');
    }

    #[Route('/post-edit/{id}', name: 'app_post_edit')]
    public function editPost(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdatedAt(new DateTimeImmutable());
            $this->entityManager->flush();

            $this->addFlash('success', 'Le post a été modifié avec succès.');

            return $this->redirectToRoute('app_post');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posts/by-subject/{id}', name: 'app_posts_by_subject')]
    public function showBySubject($id): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findBy(['subject' => $id]);
        $subject = $this->entityManager->getRepository(Subject::class)->find($id);

        return $this->render('home/posts_by_subject.html.twig', [
            'posts' => $posts,
            'subject' => $subject,
        ]);
    }

    #[Route('/post/comments/{id}', name: 'app_post_comments')]
    public function postComments(int $id, Request $request): Response
    {
        $post = $this->entityManager->getRepository(Post::class)->find($id);

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentContent = $commentForm->get('content')->getData();
            if (!empty($commentContent)) {
                $comment->setPost($post);
                $comment->setAuthor($this->getUser());
                $comment->setContent($commentContent);
                
                $this->entityManager->persist($comment);
                $this->entityManager->flush();
                
                return $this->redirectToRoute('app_post_comments', ['id' => $id]);
            } else {
                $this->addFlash('error', 'Le contenu du commentaire ne peut pas être vide.');
            }

        }
        $comments = $post->getComments();

        return $this->render('post/comments.html.twig', [
            'post' => $post,
            'comment_form' => $commentForm->createView(),
            'postComments' => $comments
        ]);
    }
}
