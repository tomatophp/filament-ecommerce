<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources;

use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use TomatoPHP\FilamentAccounts\Components\AccountColumn;
use TomatoPHP\FilamentEcommerce\Facades\FilamentEcommerce;
use TomatoPHP\FilamentEcommerce\Filament\Export\ExportOrders;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages\CreateOrder;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages\EditOrder;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages\ListOrders;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages\ViewOrder;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\RelationManagers;
use TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin;
use TomatoPHP\FilamentEcommerce\Models\Branch;
use TomatoPHP\FilamentEcommerce\Models\Company;
use TomatoPHP\FilamentEcommerce\Models\Coupon;
use TomatoPHP\FilamentEcommerce\Models\Delivery;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentEcommerce\Models\OrderLog;
use TomatoPHP\FilamentEcommerce\Models\Product;
use TomatoPHP\FilamentEcommerce\Models\ShippingPrice;
use TomatoPHP\FilamentEcommerce\Models\ShippingVendor;
use TomatoPHP\FilamentLocations\Models\Area;
use TomatoPHP\FilamentLocations\Models\City;
use TomatoPHP\FilamentLocations\Models\Country;
use TomatoPHP\FilamentTypes\Components\TypeColumn;
use TomatoPHP\FilamentTypes\Models\Type;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cursor-arrow-rays';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-ecommerce::messages.group');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-ecommerce::messages.orders.title');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-ecommerce::messages.orders.title');
    }

    public static function getLabel(): ?string
    {
        return trans('filament-ecommerce::messages.orders.single');
    }

    public static function form(Schema $schema): Schema
    {
        $types = Type::query()
            ->where('for', 'orders')
            ->where('type', 'status');

        return $schema
            ->components([
                TextInput::make('uuid')
                    ->disabled(fn (?Order $record) => (bool) $record?->exists)
                    ->label(trans('filament-ecommerce::messages.orders.columns.uuid'))
                    ->default(fn () => setting('ordering_stating_code') . '-' . Str::random(8))
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),

                Grid::make([
                    'sm' => 1,
                    'lg' => 12,
                ])->schema([
                    Section::make(trans('filament-ecommerce::messages.orders.sections.company'))
                        ->schema([
                            Select::make('company_id')
                                ->searchable()
                                ->default(setting('ordering_company_id'))
                                ->options(fn () => Company::query()->pluck('name', 'id')->toArray())
                                ->preload()
                                ->live()
                                ->required()
                                ->label(trans('filament-ecommerce::messages.orders.columns.company_id')),
                            Select::make('branch_id')
                                ->default(setting('ordering_direct_branch'))
                                ->searchable()
                                ->required()
                                ->options(fn (Get $get) => Branch::query()->where('company_id', $get('company_id'))->pluck('name', 'id')->toArray())
                                ->label(trans('filament-ecommerce::messages.orders.columns.branch_id')),
                            Select::make('status')
                                ->label(trans('filament-ecommerce::messages.orders.columns.status'))
                                ->searchable()
                                ->preload()
                                ->options(fn () => (clone $types)->pluck('name', 'key')->toArray())
                                ->required()
                                ->default('pending'),
                            Select::make('payment_method')
                                ->searchable()
                                ->preload()
                                ->options(fn () => Type::query()->where('for', 'orders')->where('type', 'payment_methods')->pluck('name', 'key')->toArray())
                                ->default('cash')
                                ->label(trans('filament-ecommerce::messages.orders.columns.payment_method')),
                        ])
                        ->columns(2)
                        ->columnSpanFull()
                        ->collapsible()
                        ->collapsed(fn ($record) => filled($record)),
                    Section::make(trans('filament-ecommerce::messages.orders.sections.account'))
                        ->schema([
                            Select::make('account_id')
                                ->searchable()
                                ->options(fn () => config('filament-accounts.model')::query()->where('is_active', 1)->pluck('name', 'id')->toArray())
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $account = config('filament-accounts.model')::find($get('account_id'));
                                    if ($account) {
                                        $set('name', $account->name);
                                        $set('phone', $account->phone);
                                    }
                                })
                                ->label(trans('filament-ecommerce::messages.orders.columns.account_id'))
                                ->required(),
                            TextInput::make('name')
                                ->label(trans('filament-ecommerce::messages.orders.columns.name'))
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label(trans('filament-ecommerce::messages.orders.columns.phone'))
                                ->maxLength(255),
                            Select::make('source')
                                ->searchable()
                                ->options(fn () => Type::query()->where('for', 'orders')->where('type', 'source')->pluck('name', 'key')->toArray())
                                ->label(trans('filament-ecommerce::messages.orders.columns.source'))
                                ->required()
                                ->default('system'),
                        ])
                        ->columnSpan(6)
                        ->collapsible()
                        ->collapsed(fn ($record) => filled($record)),
                    Section::make(trans('filament-ecommerce::messages.orders.sections.location'))
                        ->schema([
                            Select::make('country_id')
                                ->preload()
                                ->searchable()
                                ->live()
                                ->options(fn () => Country::query()->pluck('name', 'id')->toArray())
                                ->label(trans('filament-ecommerce::messages.orders.columns.country_id'))
                                ->columnSpanFull(),
                            Select::make('city_id')
                                ->searchable()
                                ->live()
                                ->options(fn (Get $get) => City::query()->where('country_id', $get('country_id'))->pluck('name', 'id')->toArray())
                                ->label(trans('filament-ecommerce::messages.orders.columns.city_id')),
                            Select::make('area_id')
                                ->searchable()
                                ->options(fn (Get $get) => Area::query()->where('city_id', $get('city_id'))->pluck('name', 'id')->toArray())
                                ->label(trans('filament-ecommerce::messages.orders.columns.area_id')),
                            TextInput::make('flat')
                                ->label(trans('filament-ecommerce::messages.orders.columns.flat'))
                                ->columnSpanFull()
                                ->maxLength(255),
                            Textarea::make('address')
                                ->label(trans('filament-ecommerce::messages.orders.columns.address'))
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->columnSpan(6)
                        ->collapsible()
                        ->collapsed(fn ($record) => filled($record)),
                ])->columnSpanFull(),
                Section::make(trans('filament-ecommerce::messages.orders.sections.items'))
                    ->schema([
                        Repeater::make('items')
                            ->hiddenLabel()
                            ->label(trans('filament-ecommerce::messages.orders.columns.items'))
                            ->schema([
                                Select::make('product_id')
                                    ->searchable()
                                    ->options(fn () => Product::query()->where('is_activated', 1)->pluck('name', 'id')->toArray())
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => static::fillItemPrices($get, $set))
                                    ->label(trans('filament-ecommerce::messages.orders.columns.product_id'))
                                    ->columnSpan(3),
                                TextInput::make('qty')
                                    ->live()
                                    ->label(trans('filament-ecommerce::messages.orders.columns.qty'))
                                    ->afterStateUpdated(fn (Get $get, Set $set) => static::fillItemPrices($get, $set))
                                    ->default(1)
                                    ->numeric(),
                                TextInput::make('price')
                                    ->disabled()
                                    ->label(trans('filament-ecommerce::messages.orders.columns.price'))
                                    ->columnSpan(2)
                                    ->default(0)
                                    ->numeric(),
                                TextInput::make('discount')
                                    ->disabled()
                                    ->label(trans('filament-ecommerce::messages.orders.columns.discount'))
                                    ->columnSpan(2)
                                    ->default(0)
                                    ->numeric(),
                                TextInput::make('vat')
                                    ->disabled()
                                    ->label(trans('filament-ecommerce::messages.orders.columns.vat'))
                                    ->columnSpan(2)
                                    ->default(0)
                                    ->numeric(),
                                TextInput::make('total')
                                    ->disabled()
                                    ->label(trans('filament-ecommerce::messages.orders.columns.total'))
                                    ->columnSpan(2)
                                    ->default(0)
                                    ->numeric(),
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $total = 0;
                                $discount = 0;
                                $vat = 0;
                                foreach ($get('items') ?? [] as $orderItem) {
                                    $product = Product::find($orderItem['product_id'] ?? null);
                                    if ($product) {
                                        $getDiscount = static::activeDiscount($product);

                                        $total += ((($product->price + $product->vat) - $getDiscount) * (float) ($orderItem['qty'] ?? 1));
                                        $discount += ($getDiscount * (float) ($orderItem['qty'] ?? 1));
                                        $vat += ($product->vat * (float) ($orderItem['qty'] ?? 1));
                                    }
                                }
                                $set('total', $total);
                                $set('discount', $discount);
                                $set('vat', $vat);
                            })
                            ->columns(12),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(fn ($record) => filled($record)),
                Section::make(trans('filament-ecommerce::messages.orders.sections.totals'))
                    ->schema([
                        Hidden::make('coupon_id'),
                        TextInput::make('coupon')
                            ->label(trans('filament-ecommerce::messages.orders.columns.coupon'))
                            ->hidden(fn ($record) => ($record || ! FilamentEcommercePlugin::$useCoupon))
                            ->dehydrated(false)
                            ->suffixAction(
                                Action::make('apply')
                                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.apply'))
                                    ->icon('heroicon-s-check')
                                    ->action(function (Get $get, Set $set) {
                                        $coupon = Coupon::query()->where('code', $get('coupon'))->first();
                                        if (! $coupon) {
                                            Notification::make()
                                                ->title(trans('filament-ecommerce::messages.orders.actions.coupon.not_found'))
                                                ->danger()
                                                ->send();

                                            return;
                                        }

                                        $total = 0;
                                        $vat = 0;
                                        $productIds = [];
                                        $discount = 0;
                                        foreach ($get('items') ?? [] as $orderItem) {
                                            $productIds[] = $orderItem['product_id'] ?? null;
                                            $product = Product::find($orderItem['product_id'] ?? null);
                                            if ($product) {
                                                $getDiscount = static::activeDiscount($product);

                                                $discount += $getDiscount;
                                                $vat += $product->vat;
                                                $total += ((($product->price + $product->vat) - $getDiscount) * (float) ($orderItem['qty'] ?? 1));
                                            }
                                        }

                                        $getCouponDiscount = FilamentEcommerce::coupon()
                                            ->products(array_filter($productIds))
                                            ->discount(code: $get('coupon'), total: $total);

                                        if ($getCouponDiscount) {
                                            $discount += $getCouponDiscount;

                                            $set('discount', $discount);
                                            $set('total', ($total + $vat) - $discount);
                                            $set('coupon_id', $coupon->id);

                                            Notification::make()
                                                ->title(trans('filament-ecommerce::messages.orders.actions.coupon.success'))
                                                ->success()
                                                ->send();
                                        } else {
                                            Notification::make()
                                                ->title(trans('filament-ecommerce::messages.orders.actions.coupon.not_valid'))
                                                ->danger()
                                                ->send();
                                        }
                                    })
                            ),
                        TextInput::make('shipping')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $total = 0;
                                foreach ($get('items') ?? [] as $orderItem) {
                                    $product = Product::find($orderItem['product_id'] ?? null);
                                    if ($product) {
                                        $total += ((($product->price + $product->vat) - static::activeDiscount($product)) * (float) ($orderItem['qty'] ?? 1));
                                    }
                                }

                                $set('total', $total + (float) $get('shipping'));
                            })
                            ->label(trans('filament-ecommerce::messages.orders.columns.shipping'))
                            ->numeric()
                            ->default(0),
                        TextInput::make('vat')
                            ->disabled()
                            ->label(trans('filament-ecommerce::messages.orders.columns.vat'))
                            ->numeric()
                            ->default(0),
                        TextInput::make('discount')
                            ->disabled()
                            ->label(trans('filament-ecommerce::messages.orders.columns.discount'))
                            ->numeric()
                            ->default(0),
                        TextInput::make('total')
                            ->disabled()
                            ->label(trans('filament-ecommerce::messages.orders.columns.total'))
                            ->numeric()
                            ->default(0),
                        Toggle::make('has_returns')
                            ->label(trans('filament-ecommerce::messages.orders.columns.has_returns'))
                            ->live(),
                        TextInput::make('return_total')
                            ->label(trans('filament-ecommerce::messages.orders.columns.return_total'))
                            ->hidden(fn (Get $get) => ! $get('has_returns'))
                            ->numeric()
                            ->default(0),
                        TextInput::make('reason')
                            ->label(trans('filament-ecommerce::messages.orders.columns.reason'))
                            ->hidden(fn (Get $get) => ! $get('has_returns'))
                            ->maxLength(255),
                        Textarea::make('notes')
                            ->label(trans('filament-ecommerce::messages.orders.columns.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(fn ($record) => filled($record)),
            ]);
    }

    public static function table(Table $table): Table
    {
        $types = Type::query()
            ->where('for', 'orders')
            ->where('type', 'status');

        return $table
            ->headerActions([
                ExportAction::make()
                    ->hidden(fn () => ! FilamentEcommercePlugin::$allowOrderExport)
                    ->hiddenLabel()
                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.export'))
                    ->color('success')
                    ->icon('heroicon-s-document-arrow-down')
                    ->exporter(ExportOrders::class),
                Action::make('import')
                    ->hidden(fn () => ! FilamentEcommercePlugin::$allowOrderImport)
                    ->hiddenLabel()
                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.import'))
                    ->color('warning')
                    ->schema([
                        Textarea::make('data')
                            ->default("name: \nphone: \naddress: \nsource: \nitems: SKU*QTY,SKU*QTY")
                            ->hint(trans('filament-ecommerce::messages.orders.import.hint'))
                            ->label(trans('filament-ecommerce::messages.orders.import.order_text'))
                            ->autosize()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        foreach (explode('====', $data['data']) as $orderText) {
                            static::convertTextToOrder($orderText);
                        }
                    })
                    ->icon('heroicon-s-document-text'),
            ])
            ->columns([
                AccountColumn::make('account.id')
                    ->label(trans('filament-ecommerce::messages.orders.columns.account_id'))
                    ->hidden(fn () => ! FilamentEcommercePlugin::$showOrderAccount)
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(trans('filament-ecommerce::messages.orders.columns.created_at'))
                    ->description(fn ($record) => $record->created_at?->diffForHumans())
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->description(fn ($record) => $record->type . ' by ' . $record->user?->name)
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TypeColumn::make('status')
                    ->label(trans('filament-ecommerce::messages.orders.columns.status'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.name'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->description(fn ($record) => $record->phone)
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(trans('filament-ecommerce::messages.orders.columns.phone'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('address')
                    ->label(trans('filament-ecommerce::messages.orders.columns.address'))
                    ->description(fn ($record) => collect([$record->country?->name, $record->city?->name, $record->area?->name, $record->flat])->filter()->implode(', '))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('shipper.name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.shipper_id'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label(trans('filament-ecommerce::messages.orders.columns.branch_id'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TypeColumn::make('payment_method')
                    ->label(trans('filament-ecommerce::messages.orders.columns.payment_method'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TypeColumn::make('source')
                    ->label(trans('filament-ecommerce::messages.orders.columns.source'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('shipping')
                    ->label(trans('filament-ecommerce::messages.orders.columns.shipping'))
                    ->summarize(Sum::make()->money(locale: 'en', currency: setting('site_currency')))
                    ->money(locale: 'en', currency: setting('site_currency'))
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('vat')
                    ->label(trans('filament-ecommerce::messages.orders.columns.vat'))
                    ->summarize(Sum::make()->money(locale: 'en', currency: setting('site_currency')))
                    ->money(locale: 'en', currency: setting('site_currency'))
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('discount')
                    ->label(trans('filament-ecommerce::messages.orders.columns.discount'))
                    ->summarize(Sum::make()->money(locale: 'en', currency: setting('site_currency')))
                    ->money(locale: 'en', currency: setting('site_currency'))
                    ->color('danger')
                    ->sortable(),
                TextColumn::make('total')
                    ->label(trans('filament-ecommerce::messages.orders.columns.total'))
                    ->summarize(Sum::make()->money(locale: 'en', currency: setting('site_currency')))
                    ->money(locale: 'en', currency: setting('site_currency'))
                    ->color('success')
                    ->sortable(),
                ToggleColumn::make('is_approved')
                    ->label(trans('filament-ecommerce::messages.orders.columns.is_approved'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('is_closed')
                    ->label(trans('filament-ecommerce::messages.orders.columns.is_closed'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(trans('filament-ecommerce::messages.orders.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('status'),
            ])
            ->filtersLayout(FiltersLayout::Modal)
            ->filters([
                SelectFilter::make('status')
                    ->label(trans('filament-ecommerce::messages.orders.filters.status'))
                    ->searchable()
                    ->options(fn () => (clone $types)->pluck('name', 'key')->toArray()),
                Filter::make('company')
                    ->label(trans('filament-ecommerce::messages.orders.filters.company'))
                    ->schema([
                        Select::make('company_id')
                            ->label(trans('filament-ecommerce::messages.orders.filters.company'))
                            ->searchable()
                            ->options(fn () => Company::query()->pluck('name', 'id')->toArray())
                            ->live(),
                        Select::make('branch_id')
                            ->searchable()
                            ->options(fn (Get $get) => Branch::query()->where('company_id', $get('company_id'))->pluck('name', 'id')->toArray())
                            ->label(trans('filament-ecommerce::messages.orders.filters.branch_id')),
                    ])
                    ->query(
                        fn (Builder $query, array $data) => $query
                            ->when($data['company_id'] ?? null, fn (Builder $query, $companyId) => $query->where('company_id', $companyId))
                            ->when($data['branch_id'] ?? null, fn (Builder $query, $branchId) => $query->where('branch_id', $branchId))
                    ),
                Filter::make('location')
                    ->label(trans('filament-ecommerce::messages.orders.filters.location'))
                    ->schema([
                        Select::make('country_id')
                            ->label(trans('filament-ecommerce::messages.orders.filters.country_id'))
                            ->searchable()
                            ->options(fn () => Country::query()->pluck('name', 'id')->toArray())
                            ->live(),
                        Select::make('city_id')
                            ->label(trans('filament-ecommerce::messages.orders.filters.city_id'))
                            ->searchable()
                            ->options(fn (Get $get) => City::query()->where('country_id', $get('country_id'))->pluck('name', 'id')->toArray()),
                        Select::make('area_id')
                            ->label(trans('filament-ecommerce::messages.orders.filters.area_id'))
                            ->searchable()
                            ->options(fn (Get $get) => Area::query()->where('city_id', $get('city_id'))->pluck('name', 'id')->toArray()),
                    ])
                    ->query(
                        fn (Builder $query, array $data) => $query
                            ->when($data['country_id'] ?? null, fn (Builder $query, $countryId) => $query->where('country_id', $countryId))
                            ->when($data['city_id'] ?? null, fn (Builder $query, $cityId) => $query->where('city_id', $cityId))
                            ->when($data['area_id'] ?? null, fn (Builder $query, $areaId) => $query->where('area_id', $areaId))
                    ),
                SelectFilter::make('account_id')
                    ->label(trans('filament-ecommerce::messages.orders.filters.account_id'))
                    ->searchable()
                    ->options(fn () => config('filament-accounts.model')::query()->pluck('name', 'id')->toArray()),
                SelectFilter::make('user_id')
                    ->label(trans('filament-ecommerce::messages.orders.filters.user_id'))
                    ->searchable()
                    ->options(fn () => config('auth.providers.users.model')::query()->pluck('name', 'id')->toArray()),
                SelectFilter::make('payment_method')
                    ->label(trans('filament-ecommerce::messages.orders.filters.payment_method'))
                    ->searchable()
                    ->options([
                        'cash' => 'Cash',
                        'credit' => 'Credit',
                        'wallet' => 'Wallet',
                    ]),
                TernaryFilter::make('is_approved')
                    ->label(trans('filament-ecommerce::messages.orders.filters.is_approved')),
                TernaryFilter::make('is_closed')
                    ->label(trans('filament-ecommerce::messages.orders.filters.is_closed')),
            ])
            ->recordActions([
                Action::make('approved')
                    ->hidden(fn ($record) => $record->status !== 'pending')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update(['is_approved' => 1, 'status' => 'prepared']);

                        static::log($record, 'Order has been Approved by: ' . auth()->user()->name . ' and Total: ' . number_format($record->total, 2));

                        Notification::make()
                            ->title('Order Approved Changed')
                            ->body('Order has been Approved')
                            ->success()
                            ->send();
                    })
                    ->label(trans('filament-ecommerce::messages.orders.actions.approved'))
                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.approved'))
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->iconButton(),
                Action::make('shipping')
                    ->hidden(fn ($record) => $record->status === 'prepared' || $record->status === 'pending')
                    ->requiresConfirmation()
                    ->schema([
                        Select::make('shipping_vendor_id')
                            ->label(trans('filament-ecommerce::messages.orders.columns.shipping_vendor_id'))
                            ->searchable()
                            ->live()
                            ->options(fn () => ShippingVendor::query()->pluck('name', 'id')->toArray())
                            ->required(),
                        Select::make('shipper_id')
                            ->label(trans('filament-ecommerce::messages.orders.columns.shipper_id'))
                            ->searchable()
                            ->options(fn (Get $get) => Delivery::query()->where('shipping_vendor_id', $get('shipping_vendor_id'))->pluck('name', 'id')->toArray())
                            ->required(),
                    ])
                    ->fillForm(fn ($record) => [
                        'shipping_vendor_id' => $record->shipping_vendor_id,
                        'shipper_id' => $record->shipper_id,
                    ])
                    ->action(function (Order $record, array $data) {
                        $getShippingVendorPrices = ShippingPrice::query()
                            ->where('shipping_vendor_id', $data['shipping_vendor_id'])
                            ->where('country_id', $record->country_id)
                            ->where('city_id', $record->city_id)
                            ->where('area_id', $record->area_id)
                            ->where(fn (Builder $query) => $query->where('delivery_id', $data['shipper_id'])->orWhereNull('delivery_id'))
                            ->first();

                        $shippingPrice = $getShippingVendorPrices
                            ? $getShippingVendorPrices->price
                            : ShippingVendor::find($data['shipping_vendor_id'])?->price;

                        $record->update([
                            'shipping_vendor_id' => $data['shipping_vendor_id'],
                            'shipper_id' => $data['shipper_id'],
                            'status' => 'shipped',
                            'shipping' => $shippingPrice,
                            'total' => $record->ordersItems()->sum('total') + $shippingPrice,
                        ]);

                        static::log($record, 'Order Shipper has been selected: ' . $record->shipper?->name . ' by: ' . auth()->user()->name . ' and Total: ' . number_format($record->total, 2));

                        Notification::make()
                            ->title('Order Shipper Changed')
                            ->body('Order Shipper has been selected: ' . $record->shipper?->name)
                            ->success()
                            ->send();
                    })
                    ->label(trans('filament-ecommerce::messages.orders.actions.shipping'))
                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.shipping'))
                    ->icon('heroicon-s-truck')
                    ->color('danger')
                    ->iconButton(),
                Action::make('status')
                    ->schema([
                        Select::make('status')
                            ->label(trans('filament-ecommerce::messages.orders.columns.status'))
                            ->searchable()
                            ->options(fn () => (clone $types)->pluck('name', 'key')->toArray())
                            ->required()
                            ->default('pending'),
                    ])
                    ->fillForm(fn ($record) => [
                        'status' => $record->status,
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update(['status' => $data['status']]);

                        static::log($record, 'Order update by ' . auth()->user()->name . ' and Total: ' . number_format($record->total, 2));

                        Notification::make()
                            ->title('Order Status Changed')
                            ->body('Order status has been changed to ' . $data['status'])
                            ->success()
                            ->send();
                    })
                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.status'))
                    ->label(trans('filament-ecommerce::messages.orders.actions.status'))
                    ->icon('heroicon-s-adjustments-horizontal')
                    ->color('warning')
                    ->iconButton(),
                Action::make('print')
                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.print'))
                    ->icon('heroicon-s-printer')
                    ->openUrlInNewTab()
                    ->url(fn ($record) => route('order.print', $record->id))
                    ->iconButton(),
                ViewAction::make()
                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.show'))
                    ->iconButton(),
                EditAction::make()
                    ->tooltip(trans('filament-ecommerce::messages.orders.actions.edit'))
                    ->iconButton(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Fill a repeater item's price, discount, vat and total from the selected product.
     */
    protected static function fillItemPrices(Get $get, Set $set): void
    {
        $product = Product::find($get('product_id'));
        if (! $product) {
            return;
        }

        $discount = static::activeDiscount($product);

        $set('price', $product->price);
        $set('discount', $discount);
        $set('vat', $product->vat);
        $set('total', (($product->price + $product->vat) - $discount) * (float) $get('qty'));
    }

    protected static function activeDiscount(Product $product): float
    {
        if ($product->discount_to && Carbon::parse($product->discount_to)->isFuture()) {
            return (float) $product->discount;
        }

        return 0;
    }

    protected static function log(Order $order, string $note): void
    {
        $orderLog = new OrderLog;
        $orderLog->user_id = auth()->id();
        $orderLog->order_id = $order->id;
        $orderLog->status = $order->status;
        $orderLog->is_closed = 1;
        $orderLog->note = $note;
        $orderLog->save();
    }

    /**
     * Create an order from the "name: / phone: / address: / source: / items: SKU*QTY,..." text import.
     */
    public static function convertTextToOrder(string $text): ?Order
    {
        $fields = ['name' => null, 'phone' => null, 'address' => null, 'source' => null, 'items' => null];
        foreach (explode("\n", $text) as $textItem) {
            foreach (array_keys($fields) as $field) {
                if (str($textItem)->contains($field . ':')) {
                    $fields[$field] = trim(str($textItem)->after($field . ':')->toString());
                }
            }
        }

        if (blank($fields['phone']) && blank($fields['items'])) {
            return null;
        }

        $accountModel = config('filament-accounts.model');
        $account = $accountModel::query()->where('phone', $fields['phone'])->where('username', $fields['phone'])->first();
        if (! $account) {
            $account = $accountModel::query()->create([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'loginBy' => 'phone',
                'username' => $fields['phone'],
                'address' => $fields['address'],
            ]);
        } else {
            $account->update([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'address' => $fields['address'],
            ]);
        }

        $order = Order::query()->create([
            'uuid' => setting('ordering_stating_code') . '-' . Str::random(8),
            'company_id' => setting('ordering_company_id'),
            'branch_id' => setting('ordering_direct_branch'),
            'user_id' => auth()->id(),
            'account_id' => $account->id,
            'name' => $fields['name'],
            'phone' => $fields['phone'],
            'address' => $fields['address'],
            'source' => $fields['source'] ?: 'system',
            'status' => 'pending',
            'payment_method' => 'cash',
            'total' => 0,
            'vat' => 0,
            'discount' => 0,
            'shipping' => 0,
        ]);

        $total = 0;
        $vat = 0;
        $discount = 0;
        $shipping = setting('ordering_active_shipping_fees') ? (float) setting('ordering_shipping_fees') : 0;

        foreach (array_filter(explode(',', (string) $fields['items'])) as $itemText) {
            $itemParts = explode('*', $itemText);
            $product = Product::query()->where('sku', 'LIKE', '%' . str($itemParts[0])->remove(' ')->toString() . '%')->first();
            if (! $product) {
                continue;
            }

            $qty = (float) ($itemParts[1] ?? 1);
            $itemDiscount = static::activeDiscount($product);

            $order->ordersItems()->create([
                'account_id' => $account->id,
                'product_id' => $product->id,
                'qty' => $qty,
                'price' => $product->price,
                'discount' => $itemDiscount,
                'vat' => $product->vat,
                'total' => (($product->price + $product->vat) - $itemDiscount) * $qty,
            ]);

            $discount += $itemDiscount * $qty;
            $vat += $product->vat * $qty;
            $total += (($product->price + $product->vat) - $itemDiscount) * $qty;
        }

        $order->discount = $discount;
        $order->vat = $vat;
        $order->total = $total + $shipping;
        $order->shipping = $shipping;
        $order->save();

        static::log($order, 'Order created by ' . auth()->user()?->name . ' and Total: ' . number_format($order->total, 2) . ' and imported text ' . $text);

        return $order;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderLog::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
            'view' => ViewOrder::route('/{record}/show'),
        ];
    }
}
