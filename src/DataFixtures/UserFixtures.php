<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use App\Entity\WishList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $ADMIN_EMAIL = "rajawiaymen404@gmail.com";
    private $ADMIN_PSWD = "admin";

    private $USER_EMAIL = "sucrepaquet431@gmail.com";
    private $USER_PSWD = "user";

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($nbUser = 1; $nbUser <= 5; $nbUser++) {
            $user = new User();
            if ($nbUser === 1) {
                $user->setEmail($this->ADMIN_EMAIL);
                $user->setPassword($this->encoder->encodePassword($user, $this->ADMIN_PSWD));
                $user->setRoles(['ROLE_ADMIN']);
            } else if ($nbUser === 2) {
                $user->setEmail($this->USER_EMAIL);
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($this->encoder->encodePassword($user, $this->USER_PSWD));
            } else {
                $user->setEmail($faker->email());
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($this->encoder->encodePassword($user, 'azerty'));
            }

            $user->setFirstname($faker->firstName());
            $user->setPseudoname($faker->userName());

            for ($nbAddress = 1; $nbAddress <= 2; $nbAddress++) {
                $address = new Address();
                $address->setName($faker->title());
                $address->setFirstname($user->getFirstName());
                $address->setLastname($faker->lastName());
                $address->setCompany($faker->company());
                $address->setAddress($faker->address());
                $address->setCountry($faker->country());
                $address->setCity($faker->city());
                $address->setPostal($faker->postcode());
                $address->setPhone($faker->randomNumber(5, false));
                $address->setUser($user);

                $manager->persist($address);
            }

            $user->setWishList(new WishList());

            $manager->persist($user);

            //Enregistrer une reference au USER mais pas en DB
            $this->addReference('user_' . $nbUser, $user);
        }

        $manager->flush();
    }
}
