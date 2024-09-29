<?php

namespace TomatoPHP\FilamentEcommerce;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentCms\Facades\FilamentCMS;
use TomatoPHP\FilamentCms\Services\Contracts\CmsType;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderReceiptSettingsPage;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderSettingsPage;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderStatusSettingsPage;
use TomatoPHP\FilamentSettingsHub\Facades\FilamentSettingsHub;
use TomatoPHP\FilamentSettingsHub\Services\Contracts\SettingHold;


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

        $this->app->bind('filament-ecommerce', function () {
            return new \TomatoPHP\FilamentEcommerce\Services\FilamentEcommerceServices();
        });

    }

    public function boot(): void
    {
        FilamentCMS::types()->register([
            CmsType::make('product')
                ->label('Product')
                ->color('primary')
                ->icon('heroicon-o-shopping-cart')
                ->sub([
                    CmsType::make('category')
                        ->label('Category')
                        ->color('primary')
                        ->icon('heroicon-o-rectangle-group'),
                    CmsType::make('tag')
                        ->label('Tag')
                        ->color('primary')
                        ->icon('heroicon-o-tag')
                ])
        ]);
    }
}
