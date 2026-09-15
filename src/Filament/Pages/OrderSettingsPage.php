<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use TomatoPHP\FilamentEcommerce\Models\Branch;
use TomatoPHP\FilamentEcommerce\Models\Company;
use TomatoPHP\FilamentEcommerce\Settings\OrderingSettings;

class OrderSettingsPage extends SettingsPage
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
        return trans('filament-ecommerce::messages.settings.orders.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1])->schema([
                    Section::make(trans('filament-ecommerce::messages.settings.orders.sections.ordering'))
                        ->schema([
                            TextInput::make('ordering_stating_code')
                                ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_stating_code'))
                                ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_stating_code")' : null),
                            Select::make('ordering_company_id')
                                ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_company_id'))
                                ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_company_id")' : null)
                                ->searchable()
                                ->options(fn () => Company::query()->pluck('name', 'id')->toArray())
                                ->preload()
                                ->live()
                                ->required(),
                            Select::make('ordering_web_branch')
                                ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_web_branch'))
                                ->searchable()
                                ->options(fn (Get $get) => Branch::query()->where('company_id', $get('ordering_company_id'))->pluck('name', 'id')->toArray())
                                ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_web_branch")' : null),
                            Select::make('ordering_mobile_branch')
                                ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_mobile_branch'))
                                ->searchable()
                                ->options(fn (Get $get) => Branch::query()->where('company_id', $get('ordering_company_id'))->pluck('name', 'id')->toArray())
                                ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_mobile_branch")' : null),
                            Select::make('ordering_direct_branch')
                                ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_direct_branch'))
                                ->searchable()
                                ->options(fn (Get $get) => Branch::query()->where('company_id', $get('ordering_company_id'))->pluck('name', 'id')->toArray())
                                ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_direct_branch")' : null),
                        ]),
                    Section::make(trans('filament-ecommerce::messages.settings.orders.sections.shipping'))
                        ->schema([
                            Toggle::make('ordering_active_shipping_fees')
                                ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_active_shipping_fees'))
                                ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_active_shipping_fees")' : null)
                                ->live(),
                            TextInput::make('ordering_shipping_fees')
                                ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_shipping_fees'))
                                ->hint(config('filament-settings-hub.show_hint') ? 'setting("ordering_shipping_fees")' : null)
                                ->hidden(fn (Get $get) => ! $get('ordering_active_shipping_fees'))
                                ->prefix('$')
                                ->numeric(),
                        ]),
                ]),
            ]);
    }
}
