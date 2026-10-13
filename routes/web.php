<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentEcommerce\Http\Controllers\PrintOrderController;
use TomatoPHP\FilamentTranslations\Http\Middleware\LanguageMiddleware;

if (! config('filament-ecommerce.print.enabled', true)) {
    return;
}

$middleware = [
    ...config('filament-ecommerce.print.middleware', ['web', 'auth:web']),
    'signed',
];
if (class_exists(LanguageMiddleware::class)) {
    $middleware[] = LanguageMiddleware::class;
}

Route::middleware($middleware)->group(function () {
    Route::get('orders/{order:uuid}/print', PrintOrderController::class)->name('order.print');
});
