<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource;

class EditShippingVendor extends EditRecord
{
    protected static string $resource = ShippingVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
