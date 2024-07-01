<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class ShippingDeliveryBoys extends RelationManager
{
    protected static string $relationship = 'deliveries';


    /**
     * @return string|null
     */
    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.deliveries.single');
    }

    /**
     * @return string|null
     */
    public static function getModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.deliveries.single');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-ecommerce::messages.deliveries.title');
    }

    protected static function getPluralModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.deliveries.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.phone'))
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.address'))
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.is_activated')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.address'))
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.is_activated')),
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
