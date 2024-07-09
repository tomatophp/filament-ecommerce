<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Pages;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\Action;
use Filament\Pages\SettingsPage;
use TomatoPHP\FilamentEcommerce\Settings\OrderingSettings;
use TomatoPHP\FilamentSettingsHub\Settings\SitesSettings;

class InventorySettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = OrderingSettings::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function getActions(): array
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        if($tenant){
            return [
                Action::make('back')->action(fn()=> redirect()->route('filament.'.filament()->getCurrentPanel()->getId().'.pages.settings-hub', $tenant))->color('danger')->label(trans('filament-settings-hub::messages.back')),
            ];
        }

        return [
            Action::make('back')->action(fn()=> redirect()->route('filament.'.filament()->getCurrentPanel()->getId().'.pages.settings-hub'))->color('danger')->label(trans('filament-settings-hub::messages.back')),
        ];

    }

    public function getTitle(): string
    {
        return "Inventory Settings";
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1])->schema([
                TextInput::make('ordering_active_inventory'),
                TextInput::make('ordering_active_inventory_web_branc'),
                TextInput::make('ordering_active_inventory_direct_branch'),
            ])

        ];
    }
}
