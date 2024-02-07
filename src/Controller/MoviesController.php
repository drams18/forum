<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies', name: 'app_movies')]
    public function movies(): Response
    {
        return $this->render('subject/movies/index.html.twig', [
            'controller_name' => 'MoviesController',
        ]);
    }
}
