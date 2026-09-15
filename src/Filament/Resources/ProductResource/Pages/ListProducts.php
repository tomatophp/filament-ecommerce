<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;

class ListProducts extends ListRecords
{
    use Translatable;

    public ?string $activeLocale = null;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LocaleSwitcher::make(),
        ];
    }
}
