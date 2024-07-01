<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\Order;

trait DeleteOrder
{
    public function delete(): void
    {
        $this->order->ordersItems()->delete();
        $this->order->delete();
    }
}
