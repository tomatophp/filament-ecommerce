<?php

use TomatoPHP\FilamentEcommerce\Console\FilamentEcommerceInstall;

use function Pest\Laravel\artisan;

it('runs the install command', function () {
    // The real command shells out to `php artisan migrate`; skip that sub-process in tests.
    app()->bind(FilamentEcommerceInstall::class, fn () => new class extends FilamentEcommerceInstall
    {
        public array $ran = [];

        public function artisanCommand(array $command, ?bool $withOutput = false): void
        {
            $this->ran[] = $command;
        }
    });

    artisan('filament-ecommerce:install')
        ->expectsOutputToContain('Filament Ecommerce installed successfully.')
        ->assertSuccessful();
});
