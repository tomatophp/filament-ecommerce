<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CompanyBranches extends RelationManager
{
    protected static string $relationship = 'branches';

    public static $primaryColumn = 'name';

    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.branch.single');
    }

    public static function getModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.branch.single');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-ecommerce::messages.branch.title');
    }

    protected static function getPluralModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.branch.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(trans('filament-ecommerce::messages.branch.columns.name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label(trans('filament-ecommerce::messages.branch.columns.phone'))
                    ->tel()
                    ->maxLength(255),
                TextInput::make('branch_number')
                    ->label(trans('filament-ecommerce::messages.branch.columns.branch_number'))
                    ->numeric()
                    ->default(1),
                TextInput::make('address')
                    ->label(trans('filament-ecommerce::messages.branch.columns.address'))
                    ->maxLength(255),
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
                    ->label(trans('filament-ecommerce::messages.branch.columns.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(trans('filament-ecommerce::messages.branch.columns.phone'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('branch_number')
                    ->label(trans('filament-ecommerce::messages.branch.columns.branch_number'))
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label(trans('filament-ecommerce::messages.branch.columns.address'))
                    ->searchable(),
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
