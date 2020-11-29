<?php

namespace Gog\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="Gog\Repository\CartProductRepository")
 * @ORM\Table(name="cart_product",indexes={@ORM\Index(name="search_idx", columns={"id_cart", "id_product"})})
 *
 * @JMS\ExclusionPolicy("all")
 */
class CartProduct
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="Gog\Entity\Cart", inversedBy="cartProducts")
     * @ORM\JoinColumn(name="id_cart", referencedColumnName="id")
     */
    private Cart $cart;

    /**
     * @ORM\ManyToOne(targetEntity="Gog\Entity\Product", inversedBy="cartProducts")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     *
     * @JMS\Type("Gog\Entity\Product")
     * @JMS\Expose
     */
    private Product $product;

    public function __construct(Cart $cart, Product $product)
    {
        $this->cart = $cart;
        $this->product = $product;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
}
