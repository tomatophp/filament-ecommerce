<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ComparisonResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\ComparisonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComparisons extends ListRecords
{
    protected static string $resource = ComparisonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
