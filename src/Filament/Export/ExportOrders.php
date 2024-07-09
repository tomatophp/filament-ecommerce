<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Export;


use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentTypes\Models\Type;

class ExportOrders extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('company.name')->label('Company'),
            ExportColumn::make('branch.name')->label('Branch'),
            ExportColumn::make('uuid')->label('UUID'),
            ExportColumn::make('status')->state(function ($record){
                return Type::where('for', 'orders')
                    ->where('type', 'status')
                    ->where('key', $record->status)
                    ->first()
                    ->name;
            })->label('Status'),
            ExportColumn::make('name')->label('Name'),
            ExportColumn::make('phone')->label('Phone'),
            ExportColumn::make('country.name')->label('Country'),
            ExportColumn::make('city.name')->label('City'),
            ExportColumn::make('area.name')->label('Area'),
            ExportColumn::make('address')->label('Address'),
            ExportColumn::make('flat')->label('Flat'),
            ExportColumn::make('source')->label('Source'),
            ExportColumn::make('payment_method')->label('Payment Method'),
            ExportColumn::make('created_at')->label('Date'),
            ExportColumn::make('vat')->label('Vat'),
            ExportColumn::make('discount')->label('Discount'),
            ExportColumn::make('shipping')->label('Shipping'),
            ExportColumn::make('total')->label('Total'),
            ExportColumn::make('items')->label('Items')->state(function ($record){
                return $record->ordersItems()->get()->map(function ($item){
                    return [
                        'item' => $item->product->sku . '['.$item->qty. '*'.number_format((($item->price+$item->vat)-$item->discount), 2) . setting('site_currency') . "=". number_format($item->total, 2) . setting('site_currency')."]\n",
                    ];
                })->implode("item", ',');
            }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
