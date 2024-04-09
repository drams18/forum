<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Form\ThemeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    #[Route('/theme', name: 'app_theme')]
    public function theme(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $themes = $entityManager->getRepository(Theme::class)->findAll();
        $themeForm = $this->createForm(ThemeType::class)->createView();

        return $this->render('theme/index.html.twig', [
            'themes' => $themes,
            'themeForm' => $themeForm,
        ]);
    }

    #[Route('/theme/create', name: 'app_theme_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $theme->setUser($this->getUser());
            
            $entityManager->persist($theme);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau thème créé !');

            $themes = $entityManager->getRepository(Theme::class)->findAll();

            return $this->redirectToRoute('app_theme', [
                'themes' => $themes,
            ]);
        }

        return $this->render('theme/index.html.twig', [
            'themeForm' => $form->createView(),
        ]);
    }
}
