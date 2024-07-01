<?php
use \Illuminate\Support\Facades\Route;

Route::middleware('auth:web', 'web')->group(function (){
   Route::get('orders/{model}/print', function (\TomatoPHP\FilamentEcommerce\Models\Order $model){
        return view('filament-ecommerce::orders.print', compact('model'));
   })->name('order.print');
});
