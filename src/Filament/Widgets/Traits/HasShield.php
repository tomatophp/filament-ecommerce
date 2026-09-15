<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Widgets\Traits;

use Filament\Facades\Filament;
use Illuminate\Support\Str;

trait HasShield
{
    public static function canView(): bool
    {
        if (filament('filament-ecommerce')->isShieldAllowed()) {
            return (bool) Filament::auth()->user()?->can(static::getPermissionName());
        }

        return true;
    }

    protected static function getPermissionName(): string
    {
        $shieldUtils = 'BezhanSalleh\\FilamentShield\\Support\\Utils';
        $prefix = class_exists($shieldUtils) ? $shieldUtils::getWidgetPermissionPrefix() : 'widget';

        return Str::of(class_basename(static::class))
            ->prepend($prefix . '_')
            ->toString();
    }
}
