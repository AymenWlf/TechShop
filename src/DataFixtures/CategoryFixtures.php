<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture 
{
    

    public function load(ObjectManager $manager)
    {

        for($nbCategory = 1;$nbCategory <= 5;$nbCategory++)
        {
            $category = new Category();
            $category->setName('category_'.$nbCategory);

            $manager->persist($category);

            //Enregistrer une reference a utiliser mais pas en DB
            $this->addReference('category_'.$nbCategory,$category);
        }

        $manager->flush();
    }
}
