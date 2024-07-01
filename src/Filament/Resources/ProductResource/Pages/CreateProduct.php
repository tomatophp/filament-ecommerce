<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    #[Reactive]
    public ?string $activeLocale = null;

    public static function getTranslatableLocales(): array
    {
        return ['en', 'ar'];
    }

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return  [
            Actions\LocaleSwitcher::make()
        ];
    }

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $data = $this->form->getState();


        if(isset($data['prices'])){
            $record->meta('prices', $data['prices']);
        }
        if(isset($data['options'])){
            $record->meta('prices', $data['options']);
        }
    }
}
