<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\GiftCardResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\GiftCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGiftCards extends ListRecords
{
    protected static string $resource = GiftCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
