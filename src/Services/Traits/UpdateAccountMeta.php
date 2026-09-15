<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\FilamentLocations\Models\Location;

trait UpdateAccountMeta
{
    public function updateAccountMeta(Request $request): void
    {
        $account = auth('accounts')->user();
        $account->update([
            'address' => $request->input('address'),
        ]);

        $account->meta('city_id', $request->input('city_id'));
        $account->meta('country_id', $request->input('country_id'));
        $account->meta('area_id', $request->input('area_id'));
        $account->meta('payment_method', $request->input('payment_method'));
        $account->meta('shipper_id', $request->input('shipper_id'));

        // Create New Location
        $checkIfLocationExists = Location::where('model_id', $account->id)
            ->where('model_type', 'account')
            ->where('city_id', $request->input('city_id'))
            ->where('country_id', $request->input('country_id'))
            ->where('area_id', $request->input('area_id'))
            ->first();
        if (! $checkIfLocationExists) {
            $location = new Location;
            $location->model_id = $account->id;
            $location->model_type = 'account';
            $location->street = $request->input('address');
            $location->city_id = $request->input('city_id');
            $location->country_id = $request->input('country_id');
            $location->area_id = $request->input('area_id');
            $location->save();
        }

    }
}
