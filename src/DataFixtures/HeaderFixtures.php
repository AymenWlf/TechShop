<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Header;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HeaderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbHeader = 1;$nbHeader <= 3;$nbHeader++)
        {
            $header = new Header();

            $header->setName('header_'.$nbHeader);
            $header->setTopCmnt($faker->sentence(1));
            $header->setMiddleCmnt($faker->sentence(1));
            $header->setLastCmnt($faker->sentence(1));
            $header->setBtnTitle($faker->sentence(1));
            $header->setIllustration('banner_01.png');

            $manager->persist($header);
        }

        $manager->flush();
    }
}
