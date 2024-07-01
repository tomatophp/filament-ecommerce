<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\DownloadResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\DownloadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDownload extends EditRecord
{
    protected static string $resource = DownloadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
