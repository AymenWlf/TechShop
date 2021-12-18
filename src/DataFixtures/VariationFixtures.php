<?php

namespace App\DataFixtures;

use App\Entity\Variation;
use App\Entity\VariationOption;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class VariationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbVariation = 1;$nbVariation <= 5;$nbVariation++)
        {
            $variation = new Variation();
            $variation->setName('variation_'.$nbVariation);
            $variation->setIllustration('');
            
            for($nbVariationOpt = 1;$nbVariationOpt <= 5;$nbVariationOpt++)
            {
                $product = $this->getReference('product_'.$faker->numberBetween(1,20));

                $variationOpt = new VariationOption();

                $variationOpt->setName('variationOption_'.$nbVariationOpt);
                $variationOpt->setVarCode(NULL);
                $variationOpt->setVariation($variation);
                $variationOpt->setStock($faker->numberBetween(20,50));
                $variationOpt->setIllustration(NULL);
                $variationOpt->setProduct($product);

                $manager->persist($variationOpt);
            }

            $manager->persist($variation);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class
        ];
    }
}
