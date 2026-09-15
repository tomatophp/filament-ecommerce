<?php

namespace TomatoPHP\FilamentEcommerce\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TomatoPHP\FilamentEcommerce\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'type' => 'product',
            'name' => ['en' => $name, 'ar' => $name],
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'sku' => strtoupper(Str::random(8)),
            'about' => ['en' => $this->faker->sentence()],
            'price' => 100,
            'vat' => 10,
            'discount' => 0,
            'is_activated' => true,
            'is_in_stock' => true,
            'has_unlimited_stock' => true,
        ];
    }
}
