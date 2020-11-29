<?php

namespace Gog\Factory;

use Gog\Entity\Cart;

class CartFactory
{
    public function create(): Cart
    {
        return new Cart();
    }
}
