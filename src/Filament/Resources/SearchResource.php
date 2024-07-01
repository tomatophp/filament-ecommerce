<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources;

use TomatoPHP\FilamentEcommerce\Filament\Resources\SearchResource\Pages;
use TomatoPHP\FilamentEcommerce\Filament\Resources\SearchResource\RelationManagers;
use TomatoPHP\FilamentEcommerce\Models\Search;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SearchResource extends Resource
{
    protected static ?string $model = Search::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('search')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('count')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('search')
                    ->searchable(),
                Tables\Columns\TextColumn::make('count')
                    ->numeric()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSearches::route('/'),
            'create' => Pages\CreateSearch::route('/create'),
            'edit' => Pages\EditSearch::route('/{record}/edit'),
        ];
    }
}
