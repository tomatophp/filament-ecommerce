<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ProductResource::class;

    #[Reactive]
    public ?string $activeLocale = null;

    public static function getTranslatableLocales(): array
    {
        return ['en', 'ar'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make()
        ];
    }
}
