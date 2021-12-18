<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbProduct = 1;$nbProduct <= 20;$nbProduct++)
        {
            $product = new Product();

            $product->setName('product_'.$nbProduct);
            $product->setSlug('product-'.$nbProduct);
            $product->setIllustration('public/images/products/iPhone/iphone1.jpeg');
            $product->setSubtitle($faker->sentence(3));
            $product->setDescription($faker->Text(250));
            $product->setPrice(999900);
            for($i = 0;$i < 2;$i++)
            {       
                $category = $this->getReference('category_'.$faker->numberBetween(1,5));
                $product->addCategory($category);
            }
            $product->setIsBest($faker->numberBetween(0,1));

            $manager->persist($product);

            
            //Reference ...
            $this->addReference('product_'.$nbProduct,$product);
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}
