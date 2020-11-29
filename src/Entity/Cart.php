<?php

namespace Gog\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gog\Model\DTO\CartProductDTO;
use Gog\Model\Money;
use Gog\Validator\Constraints as GogAssert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @GogAssert\Cart
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ORM\OneToMany(targetEntity="Gog\Entity\CartProduct", mappedBy="cart")
     *
     * @JMS\Exclude
     */
    private ?Collection $cartProducts;

    /**
     * @JMS\Exclude
     */
    private ?ArrayCollection $products = null;

    public function __construct()
    {
        $this->cartProducts = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCartProducts(): ?Collection
    {
        return $this->cartProducts;
    }

    public function setCartProducts(?Collection $cartProducts): void
    {
        $this->cartProducts = $cartProducts;
    }

    public function addCartProduct(CartProduct $cartProduct): void
    {
        $this->cartProducts->add($cartProduct);
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("products")
     * @JMS\Type("ArrayCollection<Gog\Model\DTO\CartProductDTO>")
     */
    public function getProducts(): ?ArrayCollection
    {
        if (null === $this->products) {
            $this->products = new ArrayCollection();

            //$productIds = [];

            foreach ($this->cartProducts as $cartProduct) {
                $filteredProducts = $this->products->filter(function (CartProductDTO $cartProductDTO) use ($cartProduct) {
                    return $cartProductDTO->getProductId() === $cartProduct->getProduct()->getId();
                });

                if (0 === $filteredProducts->count()) {
                    $this->products->add(new CartProductDTO($cartProduct->getProduct()->getId()));
                } else {
                    $filteredProducts->first()->increaseQuantity();
                }
            }
        }

        return $this->products;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("totalPrice")
     */
    public function getTotalPrice(): float
    {
        $totalPrice = new Money();

        foreach ($this->cartProducts as $cartProduct) {
            $totalPrice = $totalPrice->sum($cartProduct->getProduct()->getPrice());
        }

        return $totalPrice->scale();
    }
}
