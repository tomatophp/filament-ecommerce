<?php

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use TomatoPHP\FilamentEcommerce\Database\Factories\ProductFactory;
use TomatoPHP\FilamentEcommerce\Models\Cart;
use TomatoPHP\FilamentEcommerce\Services\Cart\ProductsServices;
use TomatoPHP\FilamentEcommerce\Services\Ecommerce;

function addToCart(array $input): mixed
{
    return app(Ecommerce::class)->store(Request::create('/cart', 'POST', $input));
}

it('prices cart rows from the product and ignores client amounts', function () {
    $product = ProductFactory::new()->create(['price' => 100, 'vat' => 10, 'discount' => 5]);

    $cart = addToCart([
        'product_id' => $product->id,
        'price' => -1000,
        'discount' => 5000,
        'vat' => -10,
        'total' => -999,
        'qty' => 2,
    ]);

    expect($cart)->toBeInstanceOf(Cart::class)
        ->and((float) $cart->price)->toBe(100.0)
        ->and((float) $cart->discount)->toBe(5.0)
        ->and((float) $cart->vat)->toBe(10.0)
        ->and((int) $cart->qty)->toBe(2)
        ->and((float) $cart->total)->toBe(210.0);
});

it('rejects cart rows without a product', function () {
    addToCart(['item' => 'Free stuff', 'price' => 1, 'total' => -500, 'qty' => 1]);
})->throws(ValidationException::class);

it('rejects cart rows with an unknown product', function () {
    addToCart(['product_id' => 999999, 'total' => -500]);
})->throws(ValidationException::class);

it('rejects a quantity below one', function (mixed $qty) {
    $product = ProductFactory::new()->create();

    addToCart(['product_id' => $product->id, 'qty' => $qty]);
})->with([0, -3, 'abc'])->throws(ValidationException::class);

it('re-prices stored cart rows before checkout', function () {
    $product = ProductFactory::new()->create(['price' => 50, 'vat' => 0, 'discount' => 0]);

    $tampered = Cart::query()->create([
        'product_id' => $product->id,
        'session_id' => 'abc',
        'item' => 'Tampered',
        'price' => -100,
        'discount' => 0,
        'vat' => 0,
        'qty' => 3,
        'total' => -300,
    ]);
    $orphan = Cart::query()->create(['session_id' => 'abc', 'item' => 'No product', 'price' => 1, 'qty' => 1, 'total' => -500]);
    $negativeQty = Cart::query()->create(['product_id' => $product->id, 'session_id' => 'abc', 'item' => 'x', 'price' => 50, 'qty' => -2, 'total' => -100]);

    expect(ProductsServices::repriceCart($tampered))->toBeTrue()
        ->and((float) $tampered->refresh()->total)->toBe(150.0)
        ->and(ProductsServices::repriceCart($orphan))->toBeFalse()
        ->and(ProductsServices::repriceCart($negativeQty))->toBeFalse();
});
