<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use TomatoPHP\FilamentEcommerce\Models\Branch;
use TomatoPHP\FilamentEcommerce\Settings\OrderingSettings;

class InventorySettingsPage extends SettingsPage
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = OrderingSettings::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->action(fn () => redirect()->route('filament.' . filament()->getCurrentOrDefaultPanel()->getId() . '.pages.settings-hub', Filament::getTenant()))
                ->color('danger')
                ->label(trans('filament-settings-hub::messages.back')),
        ];
    }

    public function getTitle(): string
    {
        return 'Inventory Settings';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1])->schema([
                    Toggle::make('ordering_active_inventory'),
                    Select::make('ordering_active_inventory_web_branch')
                        ->options(fn () => Branch::query()->pluck('name', 'id')->toArray())
                        ->searchable(),
                    Select::make('ordering_active_inventory_direct_branch')
                        ->options(fn () => Branch::query()->pluck('name', 'id')->toArray())
                        ->searchable(),
                ]),
            ]);
    }
}
