<?php

namespace TomatoPHP\FilamentEcommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentEcommerce\Models\Branch;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            'company_id' => CompanyFactory::new(),
            'name' => $this->faker->unique()->city() . ' Branch',
            'phone' => $this->faker->phoneNumber(),
            'branch_number' => $this->faker->numberBetween(1, 99),
            'address' => $this->faker->streetAddress(),
        ];
    }
}
