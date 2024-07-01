<?php

namespace TomatoPHP\FilamentEcommerce\Services\Cart\Contracts;

class ProductPriceModel
{
    public float|int $price = 0;
    public float|int $discount = 0;
    public float|int $vat = 0;
    public ?string $discount_to = null;

    public function price(float|int $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function discount(float|int $discount): static
    {
        $this->discount = $discount;
        return $this;
    }

    public function vat(float|int $vat): static
    {
        $this->vat = $vat;
        return $this;
    }

    public function discountTo(string $discount_to): static
    {
        $this->discount_to = $discount_to;
        return $this;
    }

    public function collect(): float
    {
        return ($this->price + $this->vat) - $this->discount;
    }

    public function collectDiscount(): float
    {
        return $this->discount ? $this->price : 0;
    }

    public function toArray(): array
    {
        return [
            'price' => $this->price,
            'discount' => $this->discount,
            'vat' => $this->vat,
            'discount_to' => $this->discount_to,
        ];
    }
}
