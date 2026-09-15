<?php

namespace TomatoPHP\FilamentEcommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentEcommerce\Models\ShippingVendor;

class ShippingVendorFactory extends Factory
{
    protected $model = ShippingVendor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Express',
            'contact_person' => $this->faker->name(),
            'delivery_estimation' => '2 days',
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'price' => 15,
            'is_activated' => true,
        ];
    }
}
