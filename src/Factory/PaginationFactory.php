<?php

namespace Gog\Factory;

use Gog\Model\Pagination;
use Symfony\Component\HttpFoundation\Request;

class PaginationFactory
{
    public function create(Request $request): Pagination
    {
        return new Pagination(
            intval($request->get('page', 0)),
            intval($request->get('limit', 0))
        );
    }
}
