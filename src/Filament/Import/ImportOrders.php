<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Import;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentEcommerce\Models\Product;

class ImportOrders extends Importer
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('company')->label('Company'),
            ImportColumn::make('branch')->label('Branch'),
            ImportColumn::make('uuid')->label('UUID'),
            ImportColumn::make('status')->label('Status'),
            ImportColumn::make('name')->label('Name'),
            ImportColumn::make('phone')->label('Phone'),
            ImportColumn::make('country')->label('Country'),
            ImportColumn::make('city')->label('City'),
            ImportColumn::make('area')->label('Area'),
            ImportColumn::make('address')->label('Address'),
            ImportColumn::make('flat')->label('Flat'),
            ImportColumn::make('source')->label('Source'),
            ImportColumn::make('payment_method')->label('Payment Method'),
            ImportColumn::make('created_at')->label('Date'),
            ImportColumn::make('vat')->label('Vat'),
            ImportColumn::make('discount')->label('Discount'),
            ImportColumn::make('shipping')->label('Shipping'),
            ImportColumn::make('total')->label('Total'),
            ImportColumn::make('items')->label('Items'),
        ];
    }

    public function resolveRecord(): ?Order
    {
        if (empty($this->data['company'])) {
            return null;
        }

        return DB::transaction(function (): Order {
            $accountModel = config('filament-accounts.model');
            $phone = $this->data['phone'] ?? null;
            $account = $phone ? $accountModel::query()->where('phone', $phone)->first() : null;
            if (! $account) {
                $account = $accountModel::query()->create([
                    'name' => $this->data['name'] ?? $phone,
                    'phone' => $phone,
                    'username' => $phone ?? Str::uuid()->toString(),
                    'loginBy' => 'phone',
                    'address' => $this->data['address'] ?? null,
                ]);
            }

            $order = Order::query()->create([
                'company_id' => $this->data['company'] ?? null,
                'branch_id' => $this->data['branch'] ?? null,
                'uuid' => $this->data['uuid'] ?? (setting('ordering_stating_code') . '-' . Str::random(8)),
                'status' => $this->data['status'] ?? 'pending',
                'name' => $this->data['name'] ?? null,
                'phone' => $phone,
                'account_id' => $account->id,
                'country_id' => $this->data['country'] ?? null,
                'city_id' => $this->data['city'] ?? null,
                'area_id' => $this->data['area'] ?? null,
                'address' => $this->data['address'] ?? null,
                'flat' => $this->data['flat'] ?? null,
                'source' => $this->data['source'] ?? 'system',
                'payment_method' => $this->data['payment_method'] ?? null,
                'vat' => $this->data['vat'] ?? 0,
                'discount' => $this->data['discount'] ?? 0,
                'shipping' => $this->data['shipping'] ?? 0,
                'total' => $this->data['total'] ?? 0,
            ]);

            // Items are exported as "SKU[QTY*PRICE=TOTAL]" separated by commas.
            foreach (array_filter(explode(',', (string) ($this->data['items'] ?? ''))) as $item) {
                $itemParts = explode('[', explode('*', $item)[0]);
                $product = Product::query()->where('sku', trim($itemParts[0]))->first();
                if (! $product) {
                    continue;
                }

                $qty = (float) ($itemParts[1] ?? 1);
                $order->ordersItems()->create([
                    'account_id' => $account->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $product->price,
                    'vat' => $product->vat,
                    'discount' => $product->discount,
                    'total' => $qty * (($product->price + $product->vat) - $product->discount),
                ]);
            }

            return $order;
        });
    }

    /**
     * The record is fully persisted in resolveRecord(); the import columns are not order attributes.
     */
    public function fillRecord(): void {}

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your order import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
