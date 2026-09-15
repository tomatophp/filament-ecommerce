<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ComparisonResource\Pages\CreateComparison;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ComparisonResource\Pages\EditComparison;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ComparisonResource\Pages\ListComparisons;
use TomatoPHP\FilamentEcommerce\Models\Comparison;

class ComparisonResource extends Resource
{
    protected static ?string $model = Comparison::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('user_type')
                    ->maxLength(255),
                TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                TextInput::make('compare_with'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_type')
                    ->searchable(),
                TextColumn::make('product_id')
                    ->numeric()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComparisons::route('/'),
            'create' => CreateComparison::route('/create'),
            'edit' => EditComparison::route('/{record}/edit'),
        ];
    }
}
