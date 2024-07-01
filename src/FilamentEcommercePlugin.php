<?php

namespace TomatoPHP\FilamentEcommerce;

use TomatoPHP\FilamentEcommerce\Filament\Resources\BranchResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\DeliveryResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ReferralCodeResource;
use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource;
use TomatoPHP\FilamentEcommerce\Models\GiftCard;
use TomatoPHP\FilamentEcommerce\Models\ShippingVendor;


class FilamentEcommercePlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-ecommerce';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            CompanyResource::class,
            ProductResource::class,
            OrderResource::class,
//            CouponResource::class,
//            DeliveryResource::class,
//            GiftCard::class,
//            ReferralCodeResource::class,
            ShippingVendorResource::class
        ]);
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
