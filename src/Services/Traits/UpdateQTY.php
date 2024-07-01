<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\Cart;
use ProtoneMedia\Splade\Facades\Toast;
use TomatoPHP\TomatoProducts\Models\Product;

trait UpdateQTY
{
    public function updateQTY(Request $request): Cart
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'note' => 'nullable|max:65535',
        ]);

        if(auth('accounts')->user()){
            $request->merge([
                'account_id' => auth('accounts')->user()->id
            ]);
        }
        else {
            $request->merge([
                'session_id' => session()->getId()
            ]);
        }

        if($request->get('qty') < 1){
            $this->cart->delete();
        }
        else {
            $this->cart->update([
                "qty" => $request->get('qty'),
                "total" => (($this->cart->price + $this->cart->vat) - $this->cart->discount) * (int)$request->get('qty')
            ]);
        }

        return $this->cart;

    }
}
