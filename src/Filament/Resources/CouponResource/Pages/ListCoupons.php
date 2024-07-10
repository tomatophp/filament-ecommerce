<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoupons extends ManageRecords
{
    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
