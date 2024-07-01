<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use TomatoPHP\FilamentEcommerce\Models\Product;

class EditProduct extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    #[Reactive]
    public ?string $activeLocale = null;

    public static function getTranslatableLocales(): array
    {
        return ['en', 'ar'];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['prices'] = $this->getRecord()->meta('prices')??[];
        $data['options'] = $this->getRecord()->meta('options')??[];

        return $data;
    }

    protected function afterSave()
    {
        $record = $this->getRecord();
        $data = $this->form->getState();


        if(isset($data['prices'])){
            $record->meta('prices', $data['prices']);
        }
        if(isset($data['options'])){
            $record->meta('options', $data['options']);
        }
    }

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make()
        ];
    }
}
