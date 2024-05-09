<?php

namespace TomatoPHP\FilamentEcommerce;

use Illuminate\Support\ServiceProvider;


class FilamentEcommerceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentEcommerce\Console\FilamentEcommerceInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-ecommerce.php', 'filament-ecommerce');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-ecommerce.php' => config_path('filament-ecommerce.php'),
        ], 'filament-ecommerce-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-ecommerce-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-ecommerce');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-ecommerce'),
        ], 'filament-ecommerce-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-ecommerce');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-ecommerce'),
        ], 'filament-ecommerce-lang');

        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    }

    public function boot(): void
    {
        //you boot methods here
    }
}
