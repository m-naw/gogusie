<?php

namespace Gog\Factory;

use Gog\Entity\Cart;
use Gog\Entity\CartProduct;
use Gog\Entity\Product;

class CartProductFactory
{
    public function create(Cart $cart, Product $product): CartProduct
    {
        $cartProduct = new CartProduct($cart, $product);
        $cart->addCartProduct($cartProduct);

        return $cartProduct;
    }
}
