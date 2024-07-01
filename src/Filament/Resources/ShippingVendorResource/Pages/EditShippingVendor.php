<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingVendor extends EditRecord
{
    protected static string $resource = ShippingVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
