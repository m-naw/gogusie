<?php

namespace Gog\Repository;

use Doctrine\ORM\EntityRepository;
use Gog\Model\Pagination;

class ProductRepository extends EntityRepository
{
    public function findPaginated(Pagination $pagination)
    {
        $page = $pagination->getPage();
        $offset = $page > 0 ? ($page - 1) * $pagination->getLimit() : 0;

        $qb = $this->createQueryBuilder('p');

        $qb->select()
            ->setMaxResults($pagination->getLimit())
            ->setFirstResult($offset)
        ;

        return $qb->getQuery()->getResult();
    }
}
