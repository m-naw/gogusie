<?php

namespace Gog\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Cart extends Constraint
{
    const MAX_UNITS = 10;
    const MAX_PRODUCTS = 3;

    public string $messageProducts = 'Cart can contain maximum {{ max }} products';

    public string $messageUnits = 'Cart can contain maximum {{ max }} units of the same product';

    public function validatedBy()
    {
        return static::class.'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
