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
use TomatoPHP\FilamentEcommerce\Filament\Resources\SearchResource\Pages\CreateSearch;
use TomatoPHP\FilamentEcommerce\Filament\Resources\SearchResource\Pages\EditSearch;
use TomatoPHP\FilamentEcommerce\Filament\Resources\SearchResource\Pages\ListSearches;
use TomatoPHP\FilamentEcommerce\Models\Search;

class SearchResource extends Resource
{
    protected static ?string $model = Search::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('search')
                    ->required()
                    ->maxLength(255),
                TextInput::make('count')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('search')
                    ->searchable(),
                TextColumn::make('count')
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
            'index' => ListSearches::route('/'),
            'create' => CreateSearch::route('/create'),
            'edit' => EditSearch::route('/{record}/edit'),
        ];
    }
}
