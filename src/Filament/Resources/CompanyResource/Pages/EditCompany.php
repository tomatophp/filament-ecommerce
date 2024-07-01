<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
