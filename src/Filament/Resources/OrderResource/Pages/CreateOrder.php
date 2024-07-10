<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages;

use Carbon\Carbon;
use TomatoPHP\FilamentEcommerce\Facades\FilamentEcommerce;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use TomatoPHP\FilamentEcommerce\Models\Coupon;
use TomatoPHP\FilamentEcommerce\Models\OrderLog;
use TomatoPHP\FilamentEcommerce\Models\Product;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    public function afterCreate(){
        $record = $this->getRecord();
        $data = $this->form->getState();

        $vat=0;
        $discount=0;
        $total=0;
        foreach ($data['items'] as $item){
            $getProduct = Product::find($item['product_id']);
            $getDiscount = 0;
            if($getProduct->discount_to && Carbon::parse($getProduct->discount_to)->isFuture()){
                $getDiscount = $getProduct->discount;
            }

            $record->ordersItems()->create([
                'account_id' => $record->account_id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $getProduct->price,
                'vat' => $getProduct->vat,
                'discount' => $getDiscount,
                'total' => ((($getProduct->price+$getProduct->vat)-$getDiscount) * $item['qty']),
            ]);

            $vat += $getProduct->vat;
            $discount += $getDiscount;
            $total += ((($getProduct->price+$getProduct->vat)-$getDiscount) * $item['qty']);
        }

        $record->user_id =  auth()->user()->id;
        $record->vat = $vat;
        if(isset($data['coupon_id']) && $data['coupon_id']){
            $coupon = Coupon::query()->find($data['coupon_id']);
            if($coupon){
                $record->coupon_id = $coupon->id;
                $discount += FilamentEcommerce::coupon()->discount($coupon->code, $record);
            }
        }
        $record->discount = $discount;
        $record->shipping = setting('ordering_active_shipping_fees') ? (int)setting('ordering_shipping_fees') : 0;
        $record->total = $total+$record->shipping;
        $record->save();

        $orderLog = new OrderLog();
        $orderLog->user_id = auth()->user()->id;
        $orderLog->order_id = $record->id;
        $orderLog->status = $record->status;
        $orderLog->is_closed = 1;
        $orderLog->note = 'Order created by '.auth()->user()->name. ' and Total: '.number_format($record->total, 2);
        $orderLog->save();
    }
}
