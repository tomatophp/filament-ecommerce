<?php

namespace TomatoPHP\FilamentEcommerce\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TomatoPHP\FilamentEcommerce\Models\Coupon;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'type' => 'discount_coupon',
            'amount' => 10,
            'is_limited' => false,
            'is_activated' => true,
            'end_at' => now()->addMonth()->toDateString(),
        ];
    }
}
