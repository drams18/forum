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
    public function subject(Request $request): Response
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();
        $subject = $this->entityManager->getRepository(Subject::class)->findAll();

        return $this->render('subject/index.html.twig', [
            'subjects' => $subjects,
            'subject' => $subject,
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
            $subject->setCategory($form->get('theme')->getData());
            $subject->setOwner($this->getUser());

            $this->entityManager->persist($subject);
            $this->entityManager->flush();

            $this->addFlash('success', 'Nouveau sujet créé !');

            return $this->redirectToRoute('app_subject_create');
        }

        return $this->render('subject/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subject/list', name: 'app_subject_list')]
    public function listSubjects(): Response
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();

        return $this->render('subject/list.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    #[Route('/subject/edit/{id}', name: 'app_subject_edit')]
    public function editSubject(Request $request, Subject $subject): Response
    {
        $form = $this->createForm(SubjectFormType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Le sujet a été modifié avec succès.');

            return $this->redirectToRoute('app_subject_list');
        }

        return $this->render('subject/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subject/delete', name: 'app_subject_delete')]
    public function deleteSubject(Request $request): Response
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();

        if ($request->isMethod('POST')) {
            $submittedData = $request->request->all();
            $subjectId = $submittedData['subject_id'];

            $subject = $this->entityManager->getRepository(Subject::class)->find($subjectId);

            if ($subject) {
                $this->entityManager->remove($subject);
                $this->entityManager->flush();

                $this->addFlash('success', 'Le sujet a été supprimé avec succès.');
            } else {
                $this->addFlash('error', 'Le sujet n\'a pas été trouvé.');
            }

            return $this->redirectToRoute('app_subject_delete');
        }

        return $this->render('subject/delete.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    #[Route('/my-subjects', name: 'app_my_subjects')]
    public function mySubjects(): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $subjects = $user->getSubjects();

        return $this->render('subject/my-subjects.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}
