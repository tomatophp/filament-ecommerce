<?php

namespace TomatoPHP\FilamentEcommerce\Facades;

use Illuminate\Support\Facades\Facade;

class FilamentEcommerce extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-ecommerce';
    }
}
