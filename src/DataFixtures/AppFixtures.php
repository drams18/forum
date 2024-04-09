<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $themesData = [
            [
                'name' => 'Analyse medicale',
                'description' => 'Description pour Analyse medicale.',
            ],
            [
                'name' => 'Anatomie vegetale',
                'description' => 'Description pour Anatomie vegetale.',
            ],
            [
                'name' => 'Anatomie animale',
                'description' => 'Description pour Anatomie animale.',
            ],  
        ];

        foreach ($themesData as $themeData) {
            $theme = new Theme();
            $theme->setName($themeData['name']);
            $theme->setDescription($themeData['description']);
            $manager->persist($theme);

        }
        $manager->flush();
    }
}