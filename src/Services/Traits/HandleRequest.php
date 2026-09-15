<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\ShippingPrice;
use TomatoPHP\FilamentEcommerce\Models\ShippingVendor;

trait HandleRequest
{
    private function handleRequest(Request &$request): void
    {
        $request->validate([
            'account_id' => 'required|array',
            'items' => 'required|array|min:1',
        ]);

        $account = $request->input('account_id');

        $request->merge([
            'user_id' => auth()->id(),
            'branch_id' => (int) setting('ordering_direct_branch'),
            'type' => 'order',
            'source' => 'direct',
            'account_id' => (int) $account['id'],
            'name' => $request->input('account_id')['name'],
            'phone' => $request->input('account_id')['phone'],
            'discount' => collect($request->input('items'))->map(fn ($item) => $item['discount'])->sum(),
            'vat' => collect($request->input('items'))->map(fn ($item) => $item['tax'])->sum(),
            'total' => collect($request->input('items'))->map(fn ($item) => $item['total'])->sum(),
        ]);

        if (isset($account['locations'][0])) {
            $request->merge([
                'country_id' => $account['locations'][0]['country_id'],
                'area_id' => $account['locations'][0]['area_id'],
                'city_id' => $account['locations'][0]['city_id'],
                'address_id' => $account['locations'][0]['id'],
                'flat' => $account['locations'][0]['flat_number'],
                'address' => $account['locations'][0]['home_number'] . '' . $account['locations'][0]['street'],
            ]);

            $shippingVendor = ShippingVendor::first();

            $shippingPrice = ShippingPrice::query()
                ->where('country_id', $request->input('country_id'))
                ->where('city_id', $request->input('city_id'))
                ->where('area_id', $request->input('area_id'));
            $shippingPrice->where('shipping_vendor_id', $shippingVendor->id);
            $shippingPrice = $shippingPrice->first();

            if ($shippingPrice) {
                $request->merge([
                    'shipping_vendor_id' => $shippingVendor->id,
                    'shipping' => $shippingPrice->price,
                ]);
            }
        }
    }
}
