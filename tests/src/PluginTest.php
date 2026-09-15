<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource;
use TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin;
use TomatoPHP\FilamentEcommerce\FilamentEcommerceServiceProvider;

it('boots the service provider', function () {
    expect(app()->getProviders(FilamentEcommerceServiceProvider::class))->not->toBeEmpty()
        ->and(app('filament-ecommerce'))->not->toBeNull();
});

it('registers plugin', function () {
    $panel = Filament::getCurrentOrDefaultPanel();

    expect($panel->getPlugin('filament-ecommerce'))->toBeInstanceOf(FilamentEcommercePlugin::class);
});

it('registers the ecommerce resources on the panel', function () {
    $resources = Filament::getCurrentOrDefaultPanel()->getResources();

    expect($resources)
        ->toContain(CompanyResource::class)
        ->toContain(ProductResource::class)
        ->toContain(OrderResource::class)
        ->toContain(ShippingVendorResource::class)
        ->toContain(CouponResource::class);
});
