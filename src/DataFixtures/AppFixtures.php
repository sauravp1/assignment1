<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Orders;
use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName);
            $customer->setLastName($faker->lastName);
            $customer->setEmail($faker->email);
            $customer->setPhoneNumber($faker->phoneNumber);
            $manager->persist($customer);
        }

        // for ($i = 0; $i < 50; $i++) {
        //     $product = new Product();
        //     $product->setName($faker->name);
        //     $product->setCategory($faker->company);
        //     $product->setPrice($faker->numberBetween(10,1000));
        //     // $product->setPhoneNumber($faker->phoneNumber);
        //     $manager->persist($product);
        // }
        $manager->flush();
    }
}
