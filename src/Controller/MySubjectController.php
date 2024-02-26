<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MySubjectController extends AbstractController
{
    #[Route('/my/subject', name: 'app_my_subject')]
    public function mysubject(Security $security): Response
    {
        $user = $security->getUser();

        $currentUserSubjects = $user->getMySubjects();
    
        return $this->render('my_subject/index.html.twig', [
            'mySubjects' => $currentUserSubjects,
            'user' => $user,
        ]);
    }
}
