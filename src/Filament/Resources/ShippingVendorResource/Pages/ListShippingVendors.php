<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource;

class ListShippingVendors extends ManageRecords
{
    protected static string $resource = ShippingVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
