<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use TomatoPHP\FilamentEcommerce\Models\Order;

trait InteractsWithOrders
{
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
