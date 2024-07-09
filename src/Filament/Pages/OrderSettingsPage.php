<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Pages;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Pages\Actions\Action;
use Filament\Pages\SettingsPage;
use TomatoPHP\FilamentCms\Services\Contracts\Section;
use TomatoPHP\FilamentEcommerce\Filament\Pages;
use TomatoPHP\FilamentEcommerce\Models\Branch;
use TomatoPHP\FilamentEcommerce\Models\Company;
use TomatoPHP\FilamentEcommerce\Settings\OrderingSettings;
use TomatoPHP\FilamentSettingsHub\Settings\SitesSettings;

class OrderSettingsPage extends SettingsPage
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
        return trans('filament-ecommerce::messages.settings.orders.title');
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1])->schema([
                \Filament\Forms\Components\Section::make(trans('filament-ecommerce::messages.settings.orders.sections.ordering'))
                    ->schema([
                    TextInput::make('ordering_stating_code')
                        ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_stating_code'))
                        ->hint(config('filament-settings-hub.show_hint') ?'setting("ordering_stating_code")': null),
                    Select::make('ordering_company_id')
                        ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_company_id'))
                        ->hint(config('filament-settings-hub.show_hint') ?'setting("ordering_company_id")': null)
                        ->searchable()
                        ->options(Company::query()->pluck('name', 'id')->toArray())
                        ->preload()
                        ->live()
                        ->required(),
                    Select::make('ordering_web_branch')
                        ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_web_branch'))
                        ->searchable()
                        ->options(fn(Get $get) => Branch::query()->where('company_id', $get('ordering_company_id'))->pluck('name', 'id')->toArray())
                        ->hint(config('filament-settings-hub.show_hint') ?'setting("ordering_web_branch")': null),
                    Select::make('ordering_mobile_branch')
                        ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_mobile_branch'))
                        ->searchable()
                        ->options(fn(Get $get) => Branch::query()->where('company_id', $get('ordering_company_id'))->pluck('name', 'id')->toArray())
                        ->hint(config('filament-settings-hub.show_hint') ?'setting("ordering_mobile_branch")': null),
                    Select::make('ordering_direct_branch')
                        ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_direct_branch'))
                        ->searchable()
                        ->options(fn(Get $get) => Branch::query()->where('company_id', $get('ordering_company_id'))->pluck('name', 'id')->toArray())
                        ->hint(config('filament-settings-hub.show_hint') ?'setting("ordering_direct_branch")': null),

                ]),
                \Filament\Forms\Components\Section::make(trans('filament-ecommerce::messages.settings.orders.sections.shipping'))
                    ->schema([
                        Toggle::make('ordering_active_shipping_fees')
                            ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_active_shipping_fees'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("ordering_active_shipping_fees")': null)
                            ->live(),
                        TextInput::make('ordering_shipping_fees')
                            ->label(trans('filament-ecommerce::messages.settings.orders.columns.ordering_shipping_fees'))
                            ->hint(config('filament-settings-hub.show_hint') ?'setting("ordering_shipping_fees")': null)
                            ->hidden(fn(Get $get) => !$get('ordering_active_shipping_fees'))
                            ->prefix('$')
                            ->numeric()
                    ])
            ])

        ];
    }
}
