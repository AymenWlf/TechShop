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

        for($nbProduct = 1;$nbProduct <= 20;$nbProduct++)
        {
            $product = new Product();

            $product->setName('product_'.$nbProduct);
            $product->setSlug('product-'.$nbProduct);
            $product->setIllustration('');
            $product->setSubtitle($faker->sentence(3));
            $product->setDescription($faker->Text(250));
            $product->setPrice(999900);

            for($i = 0;$i < 2;$i++)
            {       
                $category = $this->getReference('category_'.$faker->numberBetween(1,5));
                $product->addCategory($category);
            }
            
            $product->setIsBest($faker->numberBetween(0,1));

            //Variations
            $variations = [
                1 => [
                    'name' => "Marque",
                    'illustration' => "",
                ],
                2 => [
                    'name' => "Couleur",
                    'illustration' => "",
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
                    for($nbVariationOpt = 1;$nbVariationOpt <= 5;$nbVariationOpt++)
                    {
                        $variationOpt = new VariationOption();
    
                        $variationOpt->setName('variationOption_'.$nbVariationOpt);
                        $variationOpt->setVarCode(NULL);
                        $variationOpt->setStock($faker->numberBetween(20,50));
                        $variationOpt->setIllustration(NULL);
                        $product->addVariationOption($variationOpt);
    
                        $variation->addVariationOption($variationOpt);
    
                        $manager->persist($variationOpt);
                    }
    
                }
    
                $manager->persist($variation);
            }
    

            /*
            $variationOpt = $this->getReference('marque');
            $product->addVariationOption($variationOpt);

            for($i = 1;$i <= 5;$i++)
            {
                $variationOpti = $this->getReference('variationOption_'.$i);
                $product->addVariationOption($variationOpti);
            }*/
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
