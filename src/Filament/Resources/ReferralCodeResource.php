<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources;

use TomatoPHP\FilamentEcommerce\Filament\Resources\ReferralCodeResource\Pages;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ReferralCodeResource\RelationManagers;
use TomatoPHP\FilamentEcommerce\Models\ReferralCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReferralCodeResource extends Resource
{
    protected static ?string $model = ReferralCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('account_id')
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('counter')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_activated'),
                Forms\Components\Toggle::make('is_public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('counter')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_activated')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean(),
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
            'index' => Pages\ListReferralCodes::route('/'),
            'create' => Pages\CreateReferralCode::route('/create'),
            'edit' => Pages\EditReferralCode::route('/{record}/edit'),
        ];
    }
}
