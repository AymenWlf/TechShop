<?php

namespace App\DataFixtures;

use App\Entity\PaiementMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PaiementMethodFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbPaiement = 1;$nbPaiement <= 1;$nbPaiement++)
        {
            $paiementM = new PaiementMethod();
            $paiementM->setName("Cash a la livraison");
            $paiementM->setValue(0);

            $manager->persist($paiementM);
        }
        $manager->flush();
    }
}
