<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Server;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create('fr_FR');

        $manager->flush();

        $user = new User();
        $user->setEmail('theo@fixture.dev')->setUsername('Theo')->setPassword('test1234');

        $manager->persist($user);

        for ($i = 0; $i < 200; $i++) {
            $server = new Server();

            $server->setName($faker->company)
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime))
                ->setOpenDay(DateTimeImmutable::createFromMutable($faker->dateTime))
                ->setWipe($faker->boolean())
                ->setWipeDate(DateTimeImmutable::createFromMutable($faker->dateTime))
                ->setType($faker->boolean())
                ->setDescription($faker->sentence($nbWords = 50, $variableNbWords = true))
                ->setClanSize($faker->numberBetween($min = 0, $max = 4))
                ->setDiscord($faker->url)
                ->setUserOwner($user);

            $manager->persist($server);
        }

        $manager->flush();
    }
}
