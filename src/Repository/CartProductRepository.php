<?php

namespace Gog\Repository;

use Doctrine\ORM\EntityRepository;
use Gog\Entity\CartProduct;

class CartProductRepository extends EntityRepository
{
    public function findOneByCartAndProduct(int $cartId, int $productId): ?CartProduct
    {
        $qb = $this->createQueryBuilder('cp');

        $qb->select()
            ->where('cp.product = :productId')
            ->andWhere('cp.cart = :cartId')
            ->setParameter('cartId', $cartId)
            ->setParameter('productId', $productId)
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
