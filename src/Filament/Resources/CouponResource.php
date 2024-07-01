<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources;

use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource\Pages;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource\RelationManagers;
use TomatoPHP\FilamentEcommerce\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255)
                    ->default('discount_coupon'),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_limited'),
                Forms\Components\DatePicker::make('end_at'),
                Forms\Components\TextInput::make('use_limit')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('use_limit_by_user')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('order_total_limit')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_activated'),
                Forms\Components\Toggle::make('is_marketing'),
                Forms\Components\TextInput::make('marketer_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('marketer_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('marketer_amount')
                    ->numeric(),
                Forms\Components\TextInput::make('marketer_amount_max')
                    ->numeric(),
                Forms\Components\Toggle::make('marketer_show_amount_max'),
                Forms\Components\Toggle::make('marketer_hide_total_sales'),
                Forms\Components\TextInput::make('is_used')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('apply_to'),
                Forms\Components\TextInput::make('except'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_limited')
                    ->boolean(),
                Tables\Columns\TextColumn::make('end_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('use_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('use_limit_by_user')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_total_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_activated')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_marketing')
                    ->boolean(),
                Tables\Columns\TextColumn::make('marketer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marketer_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marketer_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('marketer_amount_max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('marketer_show_amount_max')
                    ->boolean(),
                Tables\Columns\IconColumn::make('marketer_hide_total_sales')
                    ->boolean(),
                Tables\Columns\TextColumn::make('is_used')
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
