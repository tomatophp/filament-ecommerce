<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use TomatoPHP\FilamentEcommerce\Models\Cart;
use ProtoneMedia\Splade\Facades\Toast;
use TomatoPHP\TomatoProducts\Models\Product;

trait StoreCart
{
    public function store(Request $request){
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'note' => 'nullable|max:65535',
        ]);

        if(auth('accounts')->user()){
            $request->merge([
                'account_id' => auth('accounts')->user()->id
            ]);
        }

        $request->merge([
            'session_id' => Cookie::get('cookieName')
        ]);


        $product = Product::find($request->get('product_id'));
        if($product){
            $options = [];
            foreach ($product->meta('options') ?? [] as $key=>$option){
                $options[$key] = $request->get($key);
            }
            $request->merge([
                'item' => $product->name,
                'price' => $product->price,
                'discount' => $product->discount,
                'vat' => $product->vat,
                'total' => (($product->price + $product->vat) - $product->discount),
                'qty' => 1,
                'options' => $options
            ]);
        }

        $checkIFCartExists = Cart::where('product_id', $request->get('product_id'))
            ->where('session_id', $request->get('session_id'))
            ->whereJsonContains('options', $request->get('options'))
            ->first();

        if($checkIFCartExists){
            $checkIFCartExists->update([
                'qty' => $checkIFCartExists->qty + 1,
                'total' => $checkIFCartExists->total + (($product->price + $product->vat) - $product->discount)
            ]);

            return __('Cart updated successfully');
        }

        $cart = Cart::create($request->all());

        return $cart;
    }
}
