<?php

namespace Gog\Model;

final class Pagination
{
    const MAX_LIMIT = 3;

    private int $page;

    private int $limit;

    public function __construct(int $page, int $limit)
    {
        $this->page = $page > 0 ? $page : 1;
        $this->limit = $limit > 0 && $limit <= self::MAX_LIMIT ? $limit : self::MAX_LIMIT;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getNextPage(): int
    {
        return $this->getPage() + 1;
    }
}
