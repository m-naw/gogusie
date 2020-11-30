<?php

namespace spec\Gog\Model;

use Gog\Model\Pagination;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Pagination
 */
class PaginationSpec extends ObjectBehavior
{
    public function it_should_not_excess_limit()
    {
        $this->beConstructedWith(1, 4);

        $this->getLimit()->shouldReturn(3);
    }

    public function it_should_have_first_page()
    {
        $this->beConstructedWith(0, 4);

        $this->getPage()->shouldReturn(1);
    }
}