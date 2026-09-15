<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;

class CreateProduct extends CreateRecord
{
    use Translatable;

    public ?string $activeLocale = null;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState();

        if (isset($data['prices'])) {
            $record->meta('prices', $data['prices']);
        }
        if (isset($data['options'])) {
            $record->meta('options', $data['options']);
        }
    }
}
