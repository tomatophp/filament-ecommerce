<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\Cart;

trait UpdateQTY
{
    public function updateQTY(Request $request): Cart
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'note' => 'nullable|max:65535',
        ]);

        if (auth('accounts')->user()) {
            $request->merge([
                'account_id' => auth('accounts')->user()->id,
            ]);
        } else {
            $request->merge([
                'session_id' => session()->getId(),
            ]);
        }

        if ($request->input('qty') < 1) {
            $this->cart->delete();
        } else {
            $this->cart->update([
                'qty' => $request->input('qty'),
                'total' => (($this->cart->price + $this->cart->vat) - $this->cart->discount) * (int) $request->input('qty'),
            ]);
        }

        return $this->cart;

    }
}
