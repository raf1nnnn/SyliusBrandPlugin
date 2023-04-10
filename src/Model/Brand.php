<?php

declare(strict_types=1);

namespace Loevgaard\SyliusBrandPlugin\Model;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Brand implements BrandInterface
{
    use ToggleableTrait;

    use ProductsAwareTrait {
        ProductsAwareTrait::__construct as private __productsAwareTraitConstruct;
    }
    use ImagesAwareTrait {
        ImagesAwareTrait::__construct as private __imagesAwareTraitConstruct;
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $name;
    /**
     * @psalm-var Collection<array-key, ProductInterface>
     */
    private Collection $activeProducts;

    public function __construct()
    {
        $this->__imagesAwareTraitConstruct();
        $this->__productsAwareTraitConstruct();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function addProduct(ProductInterface $product): void
    {
        if (!$this->hasProduct($product)) {
            $product->setBrand($this);
            $this->products->add($product);
        }
    }

    public function removeProduct(ProductInterface $product): void
    {
        if ($this->hasProduct($product)) {
            $product->setBrand(null);
            $this->products->removeElement($product);
        }
    }
    public function getActiveProducts(): array
    {
        $activeProducts = [];

        foreach ($this->products as $product) {
            if ($product->isEnabled()) {
                $activeProducts[] = $product;
            }else
                continue;
        }

        return $activeProducts;

    }
}
