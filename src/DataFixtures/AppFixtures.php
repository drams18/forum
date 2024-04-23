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
                'name' => 'Basket-Ball',
                'description' => 'Sport',
            ],
            [
                'name' => 'Football',
                'description' => 'Sport',
            ],
            [
                'name' => 'Mangas',
                'description' => 'Divertissement',
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