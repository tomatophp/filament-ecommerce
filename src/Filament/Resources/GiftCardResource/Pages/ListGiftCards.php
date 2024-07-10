<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\GiftCardResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentEcommerce\Filament\Resources\GiftCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGiftCards extends ManageRecords
{
    protected static string $resource = GiftCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function ($data, $record){
                $record->currency = setting('site_currency');
                $record->save();
            }),
        ];
    }
}
