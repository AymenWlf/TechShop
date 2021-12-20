<?php

namespace App\DataFixtures;

use App\Entity\Review;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($nbReview = 1;$nbReview <= 20;$nbReview++)
        {
            $user = $this->getReference('user_'.$faker->numberBetween(1,5));
            $product = $this->getReference('product_'.$faker->numberBetween(1,10));

            $date = $faker->dateTimeBetween('-1 year', 'now');

            $review = new Review();

            $review->setTitle($faker->sentence(2));
            $review->setDescription($faker->sentence(5));
            $review->setCreatedAt($date->format('d/m/Y'));
            $review->setUser($user);
            $review->setProduct($product);

            $manager->persist($review);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProductFixtures::class
        ];
    }
}
