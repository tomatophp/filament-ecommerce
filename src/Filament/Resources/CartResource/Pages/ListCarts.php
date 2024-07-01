<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\CartResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\CartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarts extends ListRecords
{
    protected static string $resource = CartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
