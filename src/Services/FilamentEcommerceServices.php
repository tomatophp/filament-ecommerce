<?php

namespace TomatoPHP\FilamentEcommerce\Services;

use TomatoPHP\FilamentEcommerce\Services\Cart\ProductsServices;

class FilamentEcommerceServices
{
    public function order(): Ordering
    {
        return new Ordering();
    }

    public function cart(): Ecommerce
    {
        return new Ecommerce();
    }

    public function product(): ProductsServices
    {
        return new ProductsServices();
    }

    public function coupon(): Coupons
    {
        return new Coupons();
    }

}
