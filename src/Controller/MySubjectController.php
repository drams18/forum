<?php

namespace App\Controller;

use App\Entity\Basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MySubjectController extends AbstractController
{
    #[Route('/my/subject', name: 'app_my_subject')]
    public function mysubject(Security $security): Response
    {
        $user = $security->getUser();

        if ($user !== null) {

            $mySubjects = $user->getMySubjects();

            return $this->render('my_subject/index.html.twig', [
                'mySubjects' => $mySubjects,
                'user' => $user,
            ]);
        } else {

            return $this->redirectToRoute('app_my_subject');
        }
    }

    #[Route('/my/subject/delete/{id}', name: 'app_my_subject_delete')]
    public function deleteSubject(Request $request, $id): Response
    {
        $entityManager = $this->$this->getDoctrine()->getManager();
        $subject = $entityManager->getRepository(Basket::class)->find($id);

        // Vérifier si le sujet existe
        if (!$subject) {
            throw $this->createNotFoundException('Le sujet n\'existe pas.');
        }

        // Vérifier si l'utilisateur actuel est l'auteur du sujet
        if ($subject->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce sujet.');
        }

        // Supprimer le sujet
        $entityManager->remove($subject);
        $entityManager->flush();

        // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
        return $this->redirectToRoute('app_home');
    }
}
