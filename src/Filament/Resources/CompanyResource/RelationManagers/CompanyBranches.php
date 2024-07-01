<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentEcommerce\Models\Company;

class CompanyBranches extends RelationManager
{
    protected static string $relationship = 'branches';

    public static $primaryColumn = 'name';

    /**
     * @return string|null
     */
    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.branch.single');
    }

    /**
     * @return string|null
     */
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(trans('filament-ecommerce::messages.branch.columns.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(trans('filament-ecommerce::messages.branch.columns.phone'))
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch_number')
                    ->label(trans('filament-ecommerce::messages.branch.columns.branch_number'))
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('address')
                    ->label(trans('filament-ecommerce::messages.branch.columns.address'))
                    ->maxLength(255),
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
                    ->label(trans('filament-ecommerce::messages.branch.columns.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(trans('filament-ecommerce::messages.branch.columns.phone'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch_number')
                    ->label(trans('filament-ecommerce::messages.branch.columns.branch_number'))
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(trans('filament-ecommerce::messages.branch.columns.address'))
                    ->searchable(),
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
