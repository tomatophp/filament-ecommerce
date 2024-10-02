<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Widgets\Traits;

use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Illuminate\Support\Str;

trait HasShield
{
    public static function canView(): bool
    {
        if(filament('filament-ecommerce')->isShieldAllowed()){
            return Filament::auth()->user()->can(static::getPermissionName());
        }
        else {
            return true;
        }
    }

    protected static function getPermissionName(): string
    {
        return Str::of(class_basename(static::class))
            ->prepend(
                Str::of(Utils::getWidgetPermissionPrefix())
                    ->append('_')
                    ->toString()
            )
            ->toString();
    }
}
