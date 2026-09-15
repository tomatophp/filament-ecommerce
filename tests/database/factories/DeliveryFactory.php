<?php

namespace TomatoPHP\FilamentEcommerce\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentEcommerce\Models\Delivery;

class DeliveryFactory extends Factory
{
    protected $model = Delivery::class;

    public function definition(): array
    {
        return [
            'shipping_vendor_id' => ShippingVendorFactory::new(),
            'name' => $this->faker->name(),
            'phone' => $this->faker->unique()->e164PhoneNumber(),
            'address' => $this->faker->streetAddress(),
            'is_activated' => true,
        ];
    }
}
