<?php
use \Illuminate\Support\Facades\Route;

$middleware = [
    'auth:web',
    'web'
];
if (class_exists(\TomatoPHP\FilamentTranslations\Http\Middleware\LanguageMiddleware::class)){
    $middleware[] = \TomatoPHP\FilamentTranslations\Http\Middleware\LanguageMiddleware::class;
}

Route::middleware($middleware)->group(function (){
   Route::get('orders/{model}/print', function (\TomatoPHP\FilamentEcommerce\Models\Order $model){
        return view('filament-ecommerce::orders.print', compact('model'));
   })->name('order.print');
});
