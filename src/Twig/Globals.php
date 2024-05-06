<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Subject;

class ProjectTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function getGlobals(): array
    {
        $subjects = $this->entityManager->getRepository(Subject::class)->findAll();
        return [
            'subjects' => $subjects,
        ];
    }
}
