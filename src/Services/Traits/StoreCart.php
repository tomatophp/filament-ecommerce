<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use TomatoPHP\FilamentEcommerce\Models\Cart;
use TomatoPHP\FilamentEcommerce\Models\Product;
use TomatoPHP\FilamentEcommerce\Services\Cart\ProductsServices;

trait StoreCart
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'nullable|integer|min:1',
            'note' => 'nullable|max:65535',
        ]);

        $product = Product::query()->findOrFail($request->input('product_id'));

        $options = [];
        $productOptions = $product->meta('options');
        foreach (is_array($productOptions) ? $productOptions : [] as $key => $option) {
            $options[$key] = $request->input($key);
        }

        // Prices always come from the product (or its option variant), never from the request.
        $price = ProductsServices::getProductPrice($product->id, array_values(array_filter($options, 'is_string')));
        $unitTotal = max(0, $price->collect());
        $qty = (int) ($request->input('qty') ?: 1);

        $accountId = auth('accounts')->user()?->id;
        $sessionId = Cookie::get('cookieName');

        $checkIFCartExists = Cart::where('product_id', $product->id)
            ->where('session_id', $sessionId)
            ->whereJsonContains('options', $options)
            ->first();

        if ($checkIFCartExists) {
            $newQty = (int) $checkIFCartExists->qty + $qty;

            $checkIFCartExists->update([
                'price' => $price->price,
                'discount' => $price->discount,
                'vat' => $price->vat,
                'qty' => $newQty,
                'total' => $unitTotal * $newQty,
            ]);

            return __('Cart updated successfully');
        }

        return Cart::create([
            'account_id' => $accountId,
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'item' => $product->name,
            'price' => $price->price,
            'discount' => $price->discount,
            'vat' => $price->vat,
            'qty' => $qty,
            'total' => $unitTotal * $qty,
            'options' => $options,
            'note' => $request->input('note'),
        ]);
    }
}
