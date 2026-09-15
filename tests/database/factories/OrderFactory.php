<?php

namespace TomatoPHP\FilamentEcommerce\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TomatoPHP\FilamentEcommerce\Models\Order;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'uuid' => 'TOMATO-' . Str::random(8),
            'account_id' => AccountFactory::new(),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'source' => 'system',
            'status' => 'pending',
            'payment_method' => 'cash',
            'total' => 110,
            'vat' => 10,
            'discount' => 0,
            'shipping' => 0,
        ];
    }
}
