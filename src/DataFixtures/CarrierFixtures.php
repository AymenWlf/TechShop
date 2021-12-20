<?php

namespace App\DataFixtures;

use App\Entity\Carrier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CarrierFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbCarrier = 1;$nbCarrier <= 2;$nbCarrier++)
        {
            $carrier = new Carrier();

            $carrier->setName('carrier_'.$nbCarrier);
            $carrier->setDescription($faker->text(50));
            $carrier->setPrice(4500);

            $manager->persist($carrier);
        }
        
        $manager->flush();
    }
}
