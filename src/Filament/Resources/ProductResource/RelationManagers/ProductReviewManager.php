<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use TomatoPHP\FilamentAccounts\Models\Account;

class ProductReviewManager extends RelationManager
{
    protected static string $relationship = 'productReviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('account_id')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.account_id'))
                    ->searchable()
                    ->options(Account::all()->pluck('name', 'id')->toArray())
                    ->required(),
                Forms\Components\TextInput::make('rate')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.rate'))
                    ->minValue(1)
                    ->maxValue(10)
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('review')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.review'))
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.is_activated'))
                ,
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ])
            ->columns([
                Tables\Columns\TextColumn::make('account.name')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.account_id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('rate')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.rate'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.is_activated'))
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
}
