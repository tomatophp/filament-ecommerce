<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CodesManager extends RelationManager
{
    protected static string $relationship = 'codes';

    /**
     * @return string|null
     */
    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.codes.single');
    }

    /**
     * @return string|null
     */
    public static function getModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.codes.single');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-ecommerce::messages.codes.title');
    }

    protected static function getPluralModelLabel(): ?string
    {
        return trans('filament-ecommerce::messages.codes.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label(trans('filament-ecommerce::messages.codes.columns.code'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_used')
                    ->label(trans('filament-ecommerce::messages.codes.columns.is_used'))
                    ->required(),
                Forms\Components\DateTimePicker::make('used_at')
                    ->label(trans('filament-ecommerce::messages.codes.columns.used_at')),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label(trans('filament-ecommerce::messages.codes.columns.expires_date')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(trans('filament-ecommerce::messages.codes.columns.code')),
                Tables\Columns\IconColumn::make('is_used')
                    ->label(trans('filament-ecommerce::messages.codes.columns.is_used'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('used_at')
                    ->label(trans('filament-ecommerce::messages.codes.columns.used_at'))
                    ->dateTime()
                    ->placeholder(trans('filament-ecommerce::messages.codes.columns.unused')),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->placeholder(trans('filament-ecommerce::messages.codes.columns.no_expiration')),
            ])
            ->filters([
                SelectFilter::make('is_used')
                    ->options([
                        true => trans('filament-ecommerce::messages.codes.filters.used'),
                        false => trans('filament-ecommerce::messages.codes.filters.unused'),
                    ])
                    ->label(trans('filament-ecommerce::messages.codes.filters.usage_status')),
                SelectFilter::make('expiration_status')
                    ->options([
                        'expired' => trans('filament-ecommerce::messages.codes.filters.expired'),
                        'not_expired' => trans('filament-ecommerce::messages.codes.filters.not_expired'),
                        'no_expiration' => trans('filament-ecommerce::messages.codes.filters.no_expiration'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'], function (Builder $query, string $value) {
                            if ($value === 'expired') {
                                return $query->where('expires_at', '<', now())->whereNotNull('expires_at');
                            }
                            if ($value === 'not_expired') {
                                return $query->where('expires_at', '>', now());
                            }
                            if ($value === 'no_expiration') {
                                return $query->whereNull('expires_at');
                            }
                        });
                    })
                    ->label(trans('filament-ecommerce::messages.codes.filters.expiration_status')),
                Filter::make('expires_soon')
                    ->query(fn(Builder $query): Builder => $query->whereBetween('expires_at', [now(), now()->addDays(7)]))
                    ->label(trans('filament-ecommerce::messages.codes.filters.expiring_soon')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('generateCodes')
                    ->label(trans('filament-ecommerce::messages.codes.generate.generate_codes'))
                    ->action(function (RelationManager $livewire, array $data): void {
                        $livewire->ownerRecord->generateCodes(
                            $data['quantity'],
                            [
                                'prefix' => $data['prefix'],
                                'suffix' => $data['suffix'],
                                'length' => $data['code_length'],
                                'expires_at' => $data['has_expiration'] ? $data['expires_at'] : null,
                                'type' => $data['code_type'],
                                'case' => $data['code_case'],
                            ]
                        );
                    })
                    ->form([
                        Forms\Components\TextInput::make('quantity')
                            ->label(trans('filament-ecommerce::messages.codes.generate.quantity'))
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        Forms\Components\TextInput::make('prefix')
                            ->label(trans('filament-ecommerce::messages.codes.generate.prefix')),
                        Forms\Components\TextInput::make('suffix')
                            ->label(trans('filament-ecommerce::messages.codes.generate.suffix')),
                        Forms\Components\Select::make('code_type')
                            ->label(trans('filament-ecommerce::messages.codes.generate.code_type'))
                            ->options([
                                'alphanumeric' => trans('filament-ecommerce::messages.codes.generate.alphanumeric'),
                                'alphabetic' => trans('filament-ecommerce::messages.codes.generate.alphabetic'),
                                'numeric' => trans('filament-ecommerce::messages.codes.generate.numeric'),
                            ])
                            ->required()
                            ->default('alphanumeric'),
                        Forms\Components\Select::make('code_case')
                            ->label(trans('filament-ecommerce::messages.codes.generate.code_case'))
                            ->options([
                                'upper' => trans('filament-ecommerce::messages.codes.generate.upper'),
                                'lower' => trans('filament-ecommerce::messages.codes.generate.lower'),
                                'mixed' => trans('filament-ecommerce::messages.codes.generate.mixed'),
                            ])
                            ->required()
                            ->default('upper'),
                        Forms\Components\TextInput::make('code_length')
                            ->label(trans('filament-ecommerce::messages.codes.generate.code_length'))
                            ->numeric()
                            ->minValue(4)
                            ->maxValue(32)
                            ->default(8)
                            ->required(),
                        Forms\Components\Toggle::make('has_expiration')
                            ->label(trans('filament-ecommerce::messages.codes.generate.has_expiration'))
                            ->live()
                            ->default(false),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label(trans('filament-ecommerce::messages.codes.generate.expires_at'))
                            ->visible(fn(callable $get) => $get('has_expiration')),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
