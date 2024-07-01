<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\Order;

trait StoreOrder
{
    public function store(Request $request): Order
    {
        $this->handleRequest($request);
        $this->validate($request);
        $this->order->fill($request->all());
        $this->order->save();
        $this->syncItems($request);
        $this->syncMeta($request);

        $this->log(__("Order Has Been Created From Dashboard"));

        return $this->order;
    }
}
