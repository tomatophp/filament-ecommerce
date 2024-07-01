<?php

namespace TomatoPHP\FilamentEcommerce\Facades;

use Illuminate\Support\Facades\Facade;
use TomatoPHP\TomatoEcommerce\Models\Cart;

class FilamentEcommerce extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-ecommerce';
    }
}
