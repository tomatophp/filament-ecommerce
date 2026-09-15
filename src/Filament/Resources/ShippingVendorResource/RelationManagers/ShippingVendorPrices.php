<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentEcommerce\Models\Delivery;
use TomatoPHP\FilamentLocations\Models\Area;
use TomatoPHP\FilamentLocations\Models\City;
use TomatoPHP\FilamentLocations\Models\Country;

class ShippingVendorPrices extends RelationManager
{
    protected static string $relationship = 'shippingPrices';

    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.shipping_prices.single');
    }

    public static function getModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.shipping_prices.single');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-ecommerce::messages.shipping_prices.title');
    }

    protected static function getPluralModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.shipping_prices.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.type'))
                    ->searchable()
                    ->options([
                        'all' => trans('filament-ecommerce::messages.shipping_prices.columns.all'),
                        'delivery' => trans('filament-ecommerce::messages.shipping_prices.columns.delivery'),
                    ])
                    ->live()
                    ->default('all'),
                Select::make('delivery_id')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.delivery_id'))
                    ->searchable()
                    ->options(fn () => Delivery::query()->where('shipping_vendor_id', $this->getOwnerRecord()->id)->pluck('name', 'id')->toArray())
                    ->hidden(fn (Get $get) => $get('type') === 'all'),
                Select::make('country_id')
                    ->preload()
                    ->searchable()
                    ->live()
                    ->options(Country::query()->pluck('name', 'id')->toArray())
                    ->label(trans('filament-ecommerce::messages.orders.columns.country_id'))
                    ->columnSpanFull(),
                Select::make('city_id')
                    ->searchable()
                    ->live()
                    ->options(fn (Get $get) => City::where('country_id', $get('country_id'))->pluck('name', 'id')->toArray())
                    ->label(trans('filament-ecommerce::messages.orders.columns.city_id')),
                Select::make('area_id')
                    ->searchable()
                    ->options(fn (Get $get) => Area::where('city_id', $get('city_id'))->pluck('name', 'id')->toArray())
                    ->label(trans('filament-ecommerce::messages.orders.columns.area_id')),
                TextInput::make('price')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.price'))
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ])
            ->columns([
                TextColumn::make('type')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.type'))
                    ->state(fn ($record) => str($record->type)->ucfirst()->title())
                    ->badge()
                    ->searchable(),
                TextColumn::make('delivery.name')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.delivery_id'))
                    ->sortable(),
                TextColumn::make('country.name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.country_id'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('city.name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.city_id'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('area.name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.area_id'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.price'))
                    ->money()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
