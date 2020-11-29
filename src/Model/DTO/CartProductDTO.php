<?php

namespace Gog\Model\DTO;

use JMS\Serializer\Annotation as JMS;

final class CartProductDTO
{
    /**
     * @JMS\SerializedName("productId")
     */
    private int $productId;

    private int $quantity = 1;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function increaseQuantity(): void
    {
        ++$this->quantity;
    }
}
