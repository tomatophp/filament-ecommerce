<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Import;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\DB;
use TomatoPHP\FilamentEcommerce\Models\Branch;
use TomatoPHP\FilamentEcommerce\Models\Company;
use TomatoPHP\FilamentEcommerce\Models\Order;

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

        DB::transaction();

        $order = null;

        if($this->data['company']){
            $order = Order::query()->create([
                'company_id' => $this->data['company']??null,
                'branch_id' => $this->data['branch']??null,
                'uuid' => $this->data['uuid']??null,
                'status' => $this->data['status']??null,
                'name' => $this->data['name']??null,
                'phone' => $this->data['phone']??null,
                'account_id' => $this->data['phone']??null,
                'country_id' =>$this->data['country']??null,
                'city_id' => $this->data['city']??null,
                'area_id' => $this->data['area']??null,
                'address' => $this->data['address']??null,
                'flat' => $this->data['flat']??null,
                'source' => $this->data['source']??null,
                'payment_method' => $this->data['payment_method']??null,
                'created_at' => $this->data['created_at']??null,
                'vat' => $this->data['vat']??0,
                'discount' => $this->data['discount']??0,
                'shipping' => $this->data['shipping']??0,
                'total' => $this->data['total']??0,
            ]);
            if($order){
                $items = explode(',', $this->data['items']);
                foreach ($items as $item){
                    $itemExpload = explode('[',  explode('*', $item)[0]);
                    $qty = $itemExpload[1];
                    $procut = Product::where('sku', $itemExpload[0])->first();
                    if($procut){
                        $order->ordersItems()->create([
                            'product_id' => $procut->id,
                            'qty' => $qty,
                            'price' => $procut->price,
                            'vat' => $procut->vat,
                            'discount' => $procut->discount,
                            'total' => $qty * (($procut->price + $procut->vat) - $procut->discount),
                        ]);
                    }
                }
            }
        }

        DB::commit();

        return $order;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your order import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
