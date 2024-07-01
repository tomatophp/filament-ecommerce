<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\SearchResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\SearchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSearches extends ListRecords
{
    protected static string $resource = SearchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
