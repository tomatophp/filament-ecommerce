<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Support\Collection;
use TomatoPHP\FilamentEcommerce\Models\Order;

trait FindOrder
{
    public function get(): Order
    {
        $this->orderMerge($this->order);
        return $this->order;
    }
    public function find(int $id): Order
    {
        $order = Order::where('id',$id)->with('ordersItems', 'account')->first();
        $this->orderMerge($order);
        return $order;
    }

    public function findByUUID(string $uuid): Order
    {
        $order = Order::where('uuid', $uuid)->with('ordersItems', 'account')->first();
        $this->orderMerge($order);
        return $order;
    }

    public function findByUser(int $id): Collection
    {
        $orders = Order::where('account_id', $id)->with('ordersItems', 'account')->get();
        $ordersArray = collect([]);
        foreach ($orders as $order){
            $this->orderMerge($order);
            $ordersArray->push($order);
        }
        return $ordersArray;
    }

    public function findOrderItems(Order $order = null): Collection
    {
        return collect($order ? $order->ordersItems : $this->order->ordersItems)->map(function ($item){
            $item->item = $item->product()->with('productMetas', function ($q){
                $q->where('key', 'options')->first();
            })->first()->toArray();
            return $item;
        });
    }

    private function orderMerge(Order &$order): void
    {
        $order->items = $this->findOrderItems();
        $order->issue_date = $order->meta('issue_date');
        $order->due_date = $order->meta('due_date');
        $order->account_id = $order->account()->with('locations', function ($q){
            $q->where('is_main', 1)->first();
        })->with('locations.country', 'locations.area', 'locations.city')->first();
    }
}
