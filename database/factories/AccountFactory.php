<?php

namespace TomatoPHP\FilamentEcommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentAccounts\Models\Account;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        $email = $this->faker->unique()->safeEmail();

        return [
            'name' => $this->faker->name(),
            'type' => 'account',
            'address' => $this->faker->address(),
            'phone' => $this->faker->unique()->e164PhoneNumber(),
            'email' => $email,
            'username' => $email,
            'loginBy' => 'email',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'is_active' => 1,
        ];
    }
}
