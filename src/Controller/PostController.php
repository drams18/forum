<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
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

    public function __construct(EntityManagerInterface $entityManager, Security $security, PostRepository $postRepository)
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
}
