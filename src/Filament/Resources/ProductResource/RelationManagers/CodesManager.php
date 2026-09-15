<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CodesManager extends RelationManager
{
    protected static string $relationship = 'codes';

    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.codes.single');
    }

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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label(trans('filament-ecommerce::messages.codes.columns.code'))
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_used')
                    ->label(trans('filament-ecommerce::messages.codes.columns.is_used'))
                    ->required(),
                DateTimePicker::make('used_at')
                    ->label(trans('filament-ecommerce::messages.codes.columns.used_at')),
                DateTimePicker::make('expires_at')
                    ->label(trans('filament-ecommerce::messages.codes.columns.expires_date')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('code')
                    ->label(trans('filament-ecommerce::messages.codes.columns.code')),
                IconColumn::make('is_used')
                    ->label(trans('filament-ecommerce::messages.codes.columns.is_used'))
                    ->boolean(),
                TextColumn::make('used_at')
                    ->label(trans('filament-ecommerce::messages.codes.columns.used_at'))
                    ->dateTime()
                    ->placeholder(trans('filament-ecommerce::messages.codes.columns.unused')),
                TextColumn::make('expires_at')
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
                    ->query(fn (Builder $query): Builder => $query->whereBetween('expires_at', [now(), now()->addDays(7)]))
                    ->label(trans('filament-ecommerce::messages.codes.filters.expiring_soon')),
            ])
            ->headerActions([
                CreateAction::make(),
                Action::make('generateCodes')
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
                    ->schema([
                        TextInput::make('quantity')
                            ->label(trans('filament-ecommerce::messages.codes.generate.quantity'))
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('prefix')
                            ->label(trans('filament-ecommerce::messages.codes.generate.prefix')),
                        TextInput::make('suffix')
                            ->label(trans('filament-ecommerce::messages.codes.generate.suffix')),
                        Select::make('code_type')
                            ->label(trans('filament-ecommerce::messages.codes.generate.code_type'))
                            ->options([
                                'alphanumeric' => trans('filament-ecommerce::messages.codes.generate.alphanumeric'),
                                'alphabetic' => trans('filament-ecommerce::messages.codes.generate.alphabetic'),
                                'numeric' => trans('filament-ecommerce::messages.codes.generate.numeric'),
                            ])
                            ->required()
                            ->default('alphanumeric'),
                        Select::make('code_case')
                            ->label(trans('filament-ecommerce::messages.codes.generate.code_case'))
                            ->options([
                                'upper' => trans('filament-ecommerce::messages.codes.generate.upper'),
                                'lower' => trans('filament-ecommerce::messages.codes.generate.lower'),
                                'mixed' => trans('filament-ecommerce::messages.codes.generate.mixed'),
                            ])
                            ->required()
                            ->default('upper'),
                        TextInput::make('code_length')
                            ->label(trans('filament-ecommerce::messages.codes.generate.code_length'))
                            ->numeric()
                            ->minValue(4)
                            ->maxValue(32)
                            ->default(8)
                            ->required(),
                        Toggle::make('has_expiration')
                            ->label(trans('filament-ecommerce::messages.codes.generate.has_expiration'))
                            ->live()
                            ->default(false),
                        DateTimePicker::make('expires_at')
                            ->label(trans('filament-ecommerce::messages.codes.generate.expires_at'))
                            ->visible(fn (callable $get) => $get('has_expiration')),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
