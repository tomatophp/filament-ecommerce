<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ReferralCodeResource\Pages;

use TomatoPHP\FilamentEcommerce\Filament\Resources\ReferralCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReferralCode extends EditRecord
{
    protected static string $resource = ReferralCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
