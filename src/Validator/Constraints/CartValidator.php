<?php

namespace Gog\Validator\Constraints;

use Gog\Entity\Cart as CartEntity;
use Gog\Model\DTO\CartProductDTO;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CartValidator extends ConstraintValidator
{
    public function validate($cart, Constraint $constraint)
    {
        if (!$constraint instanceof Cart) {
            throw new UnexpectedTypeException($constraint, Cart::class);
        }

        if (!$cart instanceof CartEntity) {
            throw new UnexpectedValueException($cart, CartEntity::class);
        }

        if ($cart->getProducts()->count() > Cart::MAX_PRODUCTS) {
            $this->context->buildViolation($constraint->messageProducts)
                ->setParameter('{{ max }}', Cart::MAX_PRODUCTS)
                ->addViolation();
        }

        $filteredProducts = $cart->getProducts()->filter(function (CartProductDTO $cartProductDTO) {
            return $cartProductDTO->getQuantity() > Cart::MAX_UNITS;
        });

        if ($filteredProducts->count() > 0) {
            $this->context->buildViolation($constraint->messageUnits)
                ->setParameter('{{ max }}', Cart::MAX_UNITS)
                ->addViolation();
        }
    }
}
