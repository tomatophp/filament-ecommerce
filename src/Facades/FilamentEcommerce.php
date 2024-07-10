<?php

namespace TomatoPHP\FilamentEcommerce\Facades;

use Illuminate\Support\Facades\Facade;


/**
 *
 * @method static \TomatoPHP\FilamentEcommerce\Services\Ordering order()
 * @method static \TomatoPHP\FilamentEcommerce\Services\Ecommerce cart()
 * @method static \TomatoPHP\FilamentEcommerce\Services\ProductsServices product()
 * @method static \TomatoPHP\FilamentEcommerce\Services\Coupons coupon()
 *
 */
class FilamentEcommerce extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-ecommerce';
    }
}
