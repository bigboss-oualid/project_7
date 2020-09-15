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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $faker;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $handys = ['Samsung galaxy S10', 'Samsung galaxy S20', 'Samsung galaxy S10+', 'Samsung galaxy S20+', 'Samsung galaxy S20+ Ultra', 'Samsung Note10', 'Samsung Note20', 'Samsung Note10+', 'Samsung Note20+', 'Samsung Note20+ Ultra'];
        $details = ['Batterie: 4000 mAh, Mémoire: 12 Go RAM, proc: Exynos 990 Samsung, Caméra: 12Mpx', 'Batterie: 6000 mAh, Mémoire: 8 Go RAM, proc: Exynos 980 Samsung, Caméra: 12Mpx', 'Batterie: 6600 mAh, Mémoire: 16 Go RAM, proc: Exynos 1000 Samsung, Caméra: 16Mpx'];

        $this->createCategory('Handy', $manager);

        for ($c = 0; $c < 5; ++$c) {
            $this->createCustomer($manager, $c);

            $customer = $this->getReference('customer_'.$c);

            for ($u = 0; $u < mt_rand(5, 20); ++$u) {
                $this->createUser($manager, $customer);
            }
        }

        for ($p = 0; $p < mt_rand(3, 20); ++$p) {
            $category = $this->getReference('category');

            $this->createProduct($manager, $category, $p, $handys, $details);
            $product = $this->getReference('product_'.$p);
            for ($i = 0; $i < mt_rand(1, 3); ++$i) {
                $this->createImage($manager, $product, $handys, $i, $p);
            }
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function createCategory(string $name, ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName($name);

        $this->addReference('category', $category);

        $manager->persist($category);
    }

    private function createCustomer(ObjectManager $manager, int $c): void
    {
        $customer = new Customer();

        $customer->setLastName($this->faker->lastName)
            ->setFirstName($this->faker->firstName)
            ->setUsername($this->faker->userName)
            ->setEmail($this->faker->email)
            ->setPassword($this->passwordEncoder->encodePassword($customer, 'demo'))
            ->setCompany($this->faker->company)
            ->setCreatedAt($this->faker->dateTimeBetween('-6 months'));

        $this->addReference('customer_'.$c, $customer);

        $manager->persist($customer);
    }

    private function createUser(ObjectManager $manager, Customer $customer): void
    {
        $user = new User();
        $user->setLastName($this->faker->lastName)
            ->setFirstName($this->faker->firstName)
            ->setEmail($this->faker->email)
            ->setCompany($this->faker->company)
            ->setCreatedAt($this->faker->dateTimeBetween('-1 months'))
            ->setCustomer($customer);

        $manager->persist($user);
    }

    private function createProduct(ObjectManager $manager, Category $category, int $p, array $handys, array $details): void
    {
        $product = new Product();
        $product->setName($handys[$p])
            ->setBarcode($this->faker->ean13)
            ->setDescription($this->faker->paragraphs(3, true))
            ->setDetails($details[mt_rand(0, 2)])
            ->setPrice($this->faker->numberBetween(500, 1500))
            ->setQuantity($this->faker->numberBetween(50, 300))
            ->setCreatedAt($this->faker->dateTimeBetween('-8 months'))
            ->setCategory($category);
        $this->addReference('product_'.$p, $product);

        $manager->persist($product);
    }

    private function createImage(ObjectManager $manager, Product $product, array $handys, int $i, int $p): void
    {
        $image = new Image();
        $image->setName(strtolower(preg_replace('/\s+/', '_', $handys[$p]).'_'.$i))
            ->setUrl('images/handy/')
            ->setProduct($product);

        $manager->persist($image);
    }
}
