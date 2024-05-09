<?php

namespace TomatoPHP\FilamentEcommerce;

use Filament\Contracts\Plugin;
use Filament\Panel;


class FilamentEcommercePlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-ecommerce';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }
}
