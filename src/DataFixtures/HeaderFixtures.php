<?php

namespace App\DataFixtures;

use App\Entity\Header;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HeaderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbHeader = 1;$nbHeader <= 5;$nbHeader++)
        {
            $header = new Header();

            $header->setName('header_'.$nbHeader);
            $header->setTopCmnt($faker->sentence(3));
            $header->setMiddleCmnt($faker->paragraph());
            $header->setLastCmnt($faker->sentence(2));
            $header->setBtnTitle($faker->sentence(2));
            $header->setIllustration('banner_01.png');

            $manager->persist($header);
        }

        $manager->flush();
    }
}
