<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $handys = ['Samsung galaxy S10', 'Samsung galaxy S20', 'Samsung galaxy S10+', 'Samsung galaxy S20+', 'Samsung galaxy S20+ Ultra', 'Samsung Note10', 'Samsung Note20', 'Samsung Note10+', 'Samsung Note20+', 'Samsung Note20+ Ultra'];
        $details = ['Batterie: 4000 mAh, Mémoire: 12 Go RAM, proc: Exynos 990 Samsung, Caméra: 12Mpx', 'Batterie: 6000 mAh, Mémoire: 8 Go RAM, proc: Exynos 980 Samsung, Caméra: 12Mpx', 'Batterie: 6600 mAh, Mémoire: 16 Go RAM, proc: Exynos 1000 Samsung, Caméra: 16Mpx'];

        $category = new Category();
        $category->setName('Handy');
        $manager->persist($category);

        for ($c = 0; $c < 10; ++$c) {
            $customer = new Customer();

            $customer->setLastName($faker->lastName)
                ->setFirstName($faker->firstName)
                ->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setCompany($faker->company)
                ->setCreatedAt($faker->dateTimeBetween('-6 months'));

            for ($u = 0; $u < mt_rand(5, 20); ++$u) {
                $user = new User();
                $user->setLastName($faker->lastName)
                    ->setFirstName($faker->firstName)
                    ->setEmail($faker->email)
                    ->setCompany($faker->company)
                    ->setCreatedAt($faker->dateTimeBetween('-1 months'))
                    ->setCustomer($customer);

                $manager->persist($user);
            }
            $manager->persist($customer);
        }

        for ($p = 0; $p < 10; ++$p) {
            $product = new Product();
            $product->setName($handys[$p])
                ->setBarcode($faker->ean13)
                ->setDescription($faker->paragraphs(3, true))
                ->setDetails($details[mt_rand(0, 2)])
                ->setPrice($faker->numberBetween(500, 1500))
                ->setQuantity($faker->numberBetween(50, 300))
                ->setCreatedAt($faker->dateTimeBetween('-8 months'))
                ->setCategory($category);
            for ($i = 0; $i < mt_rand(1, 3); ++$i) {
                $image = new Image();
                $image->setName(preg_replace('/\s+/', '_', $handys[$p]).'_'.$i)
                    ->setUrl('images/handy/')
                    ->setProduct($product);

                $manager->persist($image);
            }
            $manager->persist($product);
        }

        $manager->flush();
    }
}
