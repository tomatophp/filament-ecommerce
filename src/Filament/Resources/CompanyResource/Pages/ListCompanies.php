<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ManageRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
