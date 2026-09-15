<?php

namespace TomatoPHP\FilamentEcommerce\Facades;

use Illuminate\Support\Facades\Facade;
use TomatoPHP\FilamentEcommerce\Services\Coupons;
use TomatoPHP\FilamentEcommerce\Services\Ecommerce;
use TomatoPHP\FilamentEcommerce\Services\Ordering;

/**
 * @method static Ordering order()
 * @method static Ecommerce cart()
 * @method static \TomatoPHP\FilamentEcommerce\Services\ProductsServices product()
 * @method static Coupons coupon()
 */
class FilamentEcommerce extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-ecommerce';
    }
}
