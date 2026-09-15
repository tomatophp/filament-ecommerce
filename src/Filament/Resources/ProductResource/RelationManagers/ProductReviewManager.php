<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentAccounts\Models\Account;

class ProductReviewManager extends RelationManager
{
    protected static string $relationship = 'productReviews';

    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.product_reviews.single');
    }

    public static function getModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.product_reviews.single');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-ecommerce::messages.product_reviews.title');
    }

    protected static function getPluralModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.product_reviews.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('account_id')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.account_id'))
                    ->searchable()
                    ->options(Account::all()->pluck('name', 'id')->toArray())
                    ->required(),
                TextInput::make('rate')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.rate'))
                    ->minValue(1)
                    ->maxValue(10)
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('review')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.review'))
                    ->columnSpanFull(),
                Toggle::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.is_activated')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ])
            ->columns([
                TextColumn::make('account.name')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.account_id'))
                    ->sortable(),
                TextColumn::make('rate')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.rate'))
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_activated')
                    ->label(trans('filament-ecommerce::messages.product_reviews.columns.is_activated'))
                    ->boolean(),
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
