<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++){
            $property = new Property();
            $property
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setSurface($faker->numberBetween(20, 300))
                ->setRooms($faker->numberBetween(1, 7))
                ->setBedrooms($faker->numberBetween(1, 7))
                ->setFloor($faker->numberBetween(0, 7))
                ->setPrice($faker->numberBetween(100000, 1000000))
                ->setHeat($faker->numberBetween(0, 1))
                ->setCity($faker->city)
				->setFilename('toto.jpg')
                ->setAddress($faker->address)
                ->setPostalCode($faker->postcode)
                ->setSold(false)
				->setCreatedat($faker->dateTime($max = 'now', $timezone = null));
            $manager->persist($property);
        };
        $manager->flush();
    }
}
