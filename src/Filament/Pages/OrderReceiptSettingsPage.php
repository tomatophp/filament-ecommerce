<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use TomatoPHP\FilamentEcommerce\Settings\OrderingSettings;

class OrderReceiptSettingsPage extends SettingsPage
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
        return trans('filament-ecommerce::messages.settings.receipt.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1])->schema([
                    Toggle::make('ordering_show_company_data')
                        ->label(trans('filament-ecommerce::messages.settings.receipt.columns.ordering_show_company_data'))
                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_show_company_data")' : null),
                    Toggle::make('ordering_show_company_logo')
                        ->label(trans('filament-ecommerce::messages.settings.receipt.columns.ordering_show_company_logo'))
                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_show_company_logo")' : null),
                    Toggle::make('ordering_show_branch_data')
                        ->label(trans('filament-ecommerce::messages.settings.receipt.columns.ordering_show_branch_data'))
                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_show_branch_data")' : null),
                    Toggle::make('ordering_show_tax_number')
                        ->label(trans('filament-ecommerce::messages.settings.receipt.columns.ordering_show_tax_number'))
                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_show_tax_number")' : null),
                    Toggle::make('ordering_show_registration_number')
                        ->label(trans('filament-ecommerce::messages.settings.receipt.columns.ordering_show_registration_number'))
                        ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_show_registration_number")' : null),
                ]),
            ]);
    }
}
