<?php

namespace Gog\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Gog\Factory\ProductFactory;
use Gog\Model\Money;
use Symfony\Component\Yaml\Yaml;

class ProductFixtures extends Fixture
{
    private ProductFactory $productFactory;

    public function __construct(ProductFactory $productFactory)
    {
        $this->productFactory = $productFactory;
    }

    public function load(ObjectManager $manager)
    {
        $fixtures = Yaml::parse(file_get_contents(__DIR__.'/Resources/products.yml'));

        foreach ($fixtures as $alias => $data) {
            $product = $this->productFactory->create($data['title'], new Money($data['price']), false);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
