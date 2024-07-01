<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentEcommerce\Models\Delivery;
use TomatoPHP\FilamentLocations\Models\City;
use TomatoPHP\FilamentLocations\Models\Country;

class ShippingVendorPrices extends RelationManager
{
    protected static string $relationship = 'shippingPrices';

    /**
     * @return string|null
     */
    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.shipping_prices.single');
    }

    /**
     * @return string|null
     */
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.type'))
                    ->searchable()
                    ->options([
                        'all' => trans('filament-ecommerce::messages.shipping_prices.columns.all'),
                        'delivery' => trans('filament-ecommerce::messages.shipping_prices.columns.delivery'),
                    ])
                    ->live()
                    ->default('all'),
                Forms\Components\Select::make('delivery_id')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.delivery_id'))
                    ->searchable()
                    ->options(fn() => Delivery::query()->where('shipping_vendor_id', $this->getOwnerRecord()->id)->pluck('name', 'id')->toArray())
                    ->hidden(fn(Forms\Get $get) => $get('type') === 'all'),
                Forms\Components\Select::make('country_id')
                    ->preload()
                    ->searchable()
                    ->live()
                    ->options(Country::query()->pluck('name', 'id')->toArray())
                    ->label(trans('filament-ecommerce::messages.orders.columns.country_id'))
                    ->columnSpanFull(),
                Forms\Components\Select::make('city_id')
                    ->searchable()
                    ->live()
                    ->options(fn(Forms\Get $get) => City::where('country_id', $get('country_id'))->pluck('name', 'id')->toArray())
                    ->label(trans('filament-ecommerce::messages.orders.columns.city_id')),
                Forms\Components\Select::make('area_id')
                    ->searchable()
                    ->options(fn(Forms\Get $get) => \TomatoPHP\FilamentLocations\Models\Area::where('city_id', $get('city_id'))->pluck('name', 'id')->toArray())
                    ->label(trans('filament-ecommerce::messages.orders.columns.area_id')),
                Forms\Components\TextInput::make('price')
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
                Tables\Actions\CreateAction::make()
            ])
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.type'))
                    ->state(fn($record) => str($record->type)->ucfirst()->title())
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery.name')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.delivery_id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.country_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.city_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area.name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.area_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(trans('filament-ecommerce::messages.shipping_prices.columns.price'))
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
