<?php

namespace Gog\Model;

final class Money
{
    const SCALE = 2;

    /**
     * @Type("integer")
     */
    private int $amount;

    public function __construct(int $amount = 0)
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setMoney(int $amount): void
    {
        $this->amount = $amount;
    }

    public function sum(Money $money): Money
    {
        return new self($this->amount + $money->amount);
    }

    public function scale(): float
    {
        return round($this->amount / pow(10, self::SCALE), self::SCALE);
    }
}
