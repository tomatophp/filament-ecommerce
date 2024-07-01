<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\OrdersItem;

trait SyncItems
{
    private function syncItems(Request $request, bool $edit = false): void
    {
        foreach ($request->get('items') as $item){
            if(isset($item['id'])){
                $orderItem = OrdersItem::find($item['id']);
                $orderItem->update([
                    "account_id" => $request->get('account_id'),
                    "product_id" => $item['item']['id'],
                    "item" => $item['item']['name'][app()->getLocale()] ?? '',
                    "qty" => $item['qty'],
                    "price" => $item['price'],
                    "discount" => $item['discount'] ?? 0,
                    "tax" => $item['tax'] ?? 0,
                    "total" => $item['total'],
                    "options" => $item['options'] ?? [],
                ]);
            }
            else {
                $this->order->ordersItems()->create([
                    "account_id" => $request->get('account_id'),
                    "product_id" => $item['item']['id'],
                    "item" => $item['item']['name'][app()->getLocale()] ?? '',
                    "qty" => $item['qty'],
                    "price" => $item['price'],
                    "discount" => $item['discount'] ?? 0,
                    "tax" => $item['tax'] ?? 0,
                    "total" => $item['total'],
                    "options" => $item['options'] ?? [],
                ]);
            }

            if($edit){
                $this->order->ordersItems()->whereNotIn('id', collect($request->get('items'))->pluck('id'))->delete();
            }
        }
    }
}
