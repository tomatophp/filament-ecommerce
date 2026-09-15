<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ComparisonResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ComparisonResource;

class EditComparison extends EditRecord
{
    protected static string $resource = ComparisonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
