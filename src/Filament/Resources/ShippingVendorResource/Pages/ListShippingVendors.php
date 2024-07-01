<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingVendors extends ManageRecords
{
    protected static string $resource = ShippingVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
