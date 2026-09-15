<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\GiftCardResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentEcommerce\Filament\Resources\GiftCardResource;

class ListGiftCards extends ManageRecords
{
    protected static string $resource = GiftCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->after(function ($data, $record) {
                $record->currency = setting('site_currency');
                $record->save();
            }),
        ];
    }
}
