<?php

namespace TomatoPHP\FilamentEcommerce\Tests\Policies;

use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return (int) $order->user_id === (int) $user->id;
    }
}
