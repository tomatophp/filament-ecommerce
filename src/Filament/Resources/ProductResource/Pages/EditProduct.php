<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;

class EditProduct extends EditRecord
{
    use Translatable;

    public ?string $activeLocale = null;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['prices'] = $this->getRecord()->meta('prices') ?? [];
        $data['options'] = $this->getRecord()->meta('options') ?? [];

        return $data;
    }

    protected function afterSave(): void
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            LocaleSwitcher::make(),
        ];
    }
}
