<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubjectController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route('/subject', name: 'app_subject')]
    public function subject(): Response
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();

        return $this->render('subject/index.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    #[Route('/subject/create', name: 'app_subject_create')]
    public function addSubject(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $subject = new Subject();
        $form = $this->createForm(SubjectFormType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject->setOwner($this->getUser());

            $this->entityManager->persist($subject);
            $this->entityManager->flush();

            $this->addFlash('success', 'Nouveau sujet créé !');

            return $this->redirectToRoute('app_subject');
        }

        return $this->render('subject/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subject/edit', name: 'app_subject_edit_list')]
    public function editListSubjects(): Response
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();

        return $this->render('subject/edit-list.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    #[Route('/subject/edit/{id}', name: 'app_subject_edit_single')]
    public function editSubject(Request $request, Subject $subject): Response
    {
        $form = $this->createForm(SubjectFormType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Le sujet a été modifié avec succès.');

            return $this->redirectToRoute('app_subject');
        }

        return $this->render('subject/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subject/delete/{id}', name: 'app_subject_delete')]
    public function deleteSubject(Subject $subject): Response
    {
        $this->entityManager->remove($subject);
        $this->entityManager->flush();

        $this->addFlash(
            'success',
            'Suppression effectuée !'
        );

        return $this->redirectToRoute('app_subject');
    }
}