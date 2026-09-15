<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ShippingDeliveryBoys extends RelationManager
{
    protected static string $relationship = 'deliveries';

    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.deliveries.single');
    }

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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.phone'))
                    ->tel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.address'))
                    ->maxLength(255),
                Toggle::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.is_activated')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.name'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.phone'))
                    ->searchable(),
                TextColumn::make('address')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.address'))
                    ->searchable(),
                ToggleColumn::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.deliveries.columns.is_activated')),
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
