<?php

namespace Gog\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gog\Model\Money;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Gog\Repository\ProductRepository")
 * @UniqueEntity(fields={"title"}, groups={"api_create", "api_update"})
 */
class Product
{
    const DEFAULT_CURRENCY = 'USD';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(groups={"api_create"})
     *
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private string $title;

    /**
     * @Assert\NotBlank(groups={"api_create"})
     * @Assert\Type(type="integer", groups={"api_create", "api_update"})
     * @Assert\PositiveOrZero(groups={"api_create", "api_update"})
     *
     * @JMS\SerializedName("priceAmount")
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $priceAmount = null;

    /**
     * @JMS\SerializedName("priceCurrency")
     *
     * @ORM\Column(type="string", nullable=false, length=3)
     */
    private string $priceCurrency;

    /**
     * @JMS\Exclude
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private bool $removable;

    public function __construct()
    {
        $this->title = '';
        $this->priceCurrency = self::DEFAULT_CURRENCY;
        $this->removable = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPriceAmount(): ?int
    {
        return $this->priceAmount;
    }

    public function setPriceAmount(?int $priceAmount): void
    {
        $this->priceAmount = $priceAmount;
    }

    public function getPriceCurrency(): string
    {
        return $this->priceCurrency;
    }

    public function isRemovable(): bool
    {
        return $this->removable;
    }

    public function setRemovable(bool $removable): void
    {
        $this->removable = $removable;
    }

    public function getPrice(): ?Money
    {
        return new Money($this->priceAmount);
    }

    public function setPrice(Money $price): void
    {
        $this->priceAmount = $price->getAmount();
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("priceAmountScaled")
     */
    public function priceAmountScaled(): float
    {
        return $this->getPrice()->scale();
    }
}
