<?php

namespace TomatoPHP\FilamentEcommerce\Services;

use TomatoPHP\FilamentEcommerce\Models\Cart;
use TomatoPHP\FilamentEcommerce\Services\Traits\StoreCart;
use TomatoPHP\FilamentEcommerce\Services\Traits\UpdateQTY;

class Ecommerce
{
    use StoreCart;
    use UpdateQTY;

    private Cart $cart;

    public function setCart(Cart $cart): static
    {
        $this->cart = $cart;
        return $this;
    }
}
