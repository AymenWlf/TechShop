<?php

namespace App\DataFixtures;

use App\Entity\Temoignage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TemoignageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbTemoignage = 1;$nbTemoignage<= 3;$nbTemoignage++)
        {
            $temoignage = new Temoignage();

            $temoignage->setName($faker->name());
            $temoignage->setTemoignage($faker->paragraph());
            $temoignage->setIllustration('profile3.jpg');
            $temoignage->setNotoriety($faker->sentence(2));

            $manager->persist($temoignage);
        }
        
        $manager->flush();
    }
}
