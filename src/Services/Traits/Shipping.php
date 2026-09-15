<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\ShippingPrice;

trait Shipping
{
    public function shipping(Request $request): void
    {
        $request->validate([
            'shipping_vendor_id' => 'required|integer|exists:shipping_vendors,id',
            'shipper_id' => 'nullable|integer|exists:deliveries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'area_id' => 'required|integer|exists:areas,id',
            'country_id' => 'required|integer|exists:countries,id',
            'address' => 'required|max:255|string',
            'shipping' => 'required|numeric',
        ]);

        // Apply Shipping Fees By Price
        $shippingPrice = ShippingPrice::query()
            ->where('country_id', $request->input('country_id'))
            ->where('city_id', $request->input('city_id'))
            ->where('area_id', $request->input('area_id'));

        if ($request->has('shipper_id') && ! empty($request->input('shipper_id'))) {
            $shippingPrice->where('delivery_id', $request->input('shipper_id'))->orWhereNull('delivery_id');
        } else {
            $shippingPrice->where('shipping_vendor_id', $request->input('shipping_vendor_id'));
        }

        $shippingPrice = $shippingPrice->first();

        if ($shippingPrice) {
            $request->merge([
                'shipping' => $shippingPrice->price,
            ]);
        }

        $this->order->update($request->all());
        $this->shipped();

        $this->log(__('Your Order Has Been Shipped Successfully By' . ':' . $this->order->shippingVendor->name));
    }
}
