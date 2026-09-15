<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use TomatoPHP\FilamentEcommerce\Models\Cart;
use TomatoPHP\FilamentEcommerce\Models\Order;

trait StoreWebOrder
{
    public function storeWebOrder(Request $request): Order | string
    {
        $request->validate([
            'address' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'country_id' => 'required|exists:countries,id',
            'area_id' => 'required|exists:areas,id',
            'shipper_id' => 'required|exists:shipping_vendors,id',
            'payment_method' => 'required|string',
        ]);

        $account = auth('accounts')->user();

        $carts = Cart::query()->where('session_id', Cookie::get('cart'))->get();
        $this->setCart($carts);

        $shipping = $this->getShippingPrice($request);
        $total = $this->carts->sum('total') + $shipping;

        $this->updateAccountMeta($request);

        if ($request->input('payment_method') === 'wallet' && (! $this->checkBalance($total))) {
            return __('Account Balance Is Not Enough');
        } else {
            $this->order->fill([
                'uuid' => $this->generateUuid(),
                'branch_id' => (int) setting('ordering_web_branch'),
                'source' => 'web',
                'account_id' => $account->id,
                'name' => $account->name,
                'phone' => $account->phone,
                'address' => $request->input('address'),
                'city_id' => $request->input('city_id'),
                'country_id' => $request->input('country_id'),
                'area_id' => $request->input('area_id'),
                'shipping_vendor_id' => $request->input('shipper_id'),
                'payment_method' => $request->input('payment_method'),
                'discount' => $carts->sum('discount'),
                'vat' => $carts->sum('vat'),
                'total' => $total,
                'shipping' => $shipping,
                'status' => 'pending',
            ]);
            $this->order->save();

            $this->syncCart($request);
            $this->syncMeta($request);

            if ($request->input('payment_method') === 'wallet') {
                $account->withdraw($total);
                $this->log(__('Account Wallet Has Made A Transaction Of') . ' ' . (string) number_format($total, 2) . setting('local_currency'));
            }

            $this->log(__('Order Has Been Created From Web'));

            return $this->order;
        }
    }
}
