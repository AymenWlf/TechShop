<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Variation;
use App\Entity\VariationOption;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbProduct = 1;$nbProduct <= 10;$nbProduct++)
        {
            $product = new Product();

            $product->setName('product_'.$nbProduct);
            $product->setSlug('product-'.$nbProduct);
            $product->setIllustration('iphone1.jpeg');
            $product->setSubtitle($faker->sentence(3));
            $product->setDescription($faker->Text(250));
            $product->setPrice(999900);

            for($i = 0;$i < 2;$i++)
            {       
                $category = $this->getReference('category_'.$faker->numberBetween(1,2));
                $product->addCategory($category);
            }
            
            $product->setIsBest($faker->numberBetween(0,1));

            //Variations
            $variations = [
                1 => [
                    'name' => "Marque",
                    'illustration' => "iphone1.jpeg",
                ],
                2 => [
                    'name' => "Couleur",
                    'illustration' => "iphone1.jpeg",
                ]
            ];

            foreach ($variations as $key => $value) {
                $variation = new Variation();
    
                $variation->setName($value['name']);
                $variation->setIllustration($value['illustration']);
    
                if($value['name'] == "Marque")
                {
                    $variationOpt = new VariationOption();
    
                    $variationOpt->setName('marque');
                    $variationOpt->setVarCode(NULL);
                    $variationOpt->setStock($faker->numberBetween(20,50));
                    $variationOpt->setIllustration(NULL);

                    $product->addVariationOption($variationOpt);
                    $variation->addVariationOption($variationOpt);
    
                    $manager->persist($variationOpt);
                }else
                {
                    for($nbVariationOpt = 1;$nbVariationOpt <= 3;$nbVariationOpt++)
                    {
                        $variationOpt = new VariationOption();
    
                        $variationOpt->setName('variationOption_'.$nbVariationOpt);
                        $variationOpt->setVarCode(NULL);
                        $variationOpt->setStock($faker->numberBetween(20,50));
                        $variationOpt->setIllustration('iphone1.jpeg');
                        $product->addVariationOption($variationOpt);
    
                        $variation->addVariationOption($variationOpt);
    
                        $manager->persist($variationOpt);
                    }
    
                }
    
                $manager->persist($variation);
            }
    
            $manager->persist($product);

            //Reference ....
            $this->addReference("product_".$nbProduct,$product);
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
