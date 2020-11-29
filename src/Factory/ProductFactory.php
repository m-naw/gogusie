<?php

namespace Gog\Factory;

use Gog\Entity\Product;
use Gog\Model\Money;

class ProductFactory
{
    public function create(string $title, Money $price, bool $removable = true): Product
    {
        $product = new Product();
        $product->setTitle($title);
        $product->setPrice($price);
        $product->setRemovable($removable);

        return $product;
    }
}
