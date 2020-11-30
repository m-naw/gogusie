<?php

namespace spec\Gog\Model;

use Gog\Model\Money;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Money
 */
class MoneySpec extends ObjectBehavior
{
    public function it_should_scale()
    {
        $this->beConstructedWith(1000);

        $this->scale()->shouldReturn(10.00);
    }

    public function it_should_sum()
    {
        $this->beConstructedWith(1000);

        $money = new Money(1500);
        $sum = $this->sum($money);

        $sum->beAnInstanceOf(Money::class);
        $sum->getAmount()->shouldReturn(2500);
    }
}