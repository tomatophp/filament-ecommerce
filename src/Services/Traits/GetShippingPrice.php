<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\ShippingPrice;

trait GetShippingPrice
{
    public function getShippingPrice(Request $request): float
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'country_id' => 'required|exists:countries,id',
            'area_id' => 'required|exists:areas,id',
            'shipper_id' => 'required|exists:shipping_vendors,id',
        ]);

        $price = ShippingPrice::where('city_id', $request->input('city_id'))
            ->where('country_id', $request->input('country_id'))
            ->where('area_id', $request->input('area_id'))
            ->where('shipping_vendor_id', $request->input('shipper_id'))
            ->first();

        return $price->price ?? 0;
    }
}
