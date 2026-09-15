<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentTranslations\Http\Middleware\LanguageMiddleware;

$middleware = [
    'auth:web',
    'web',
];
if (class_exists(LanguageMiddleware::class)) {
    $middleware[] = LanguageMiddleware::class;
}

Route::middleware($middleware)->group(function () {
    Route::get('orders/{model}/print', function (Order $model) {
        return view('filament-ecommerce::orders.print', compact('model'));
    })->name('order.print');
});
