<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\TomatoCrm\Models\Location;

trait UpdateAccountMeta
{
    public function updateAccountMeta(Request $request): void
    {
        $account = auth('accounts')->user();
        $account->update([
            'address' => $request->get('address')
        ]);

        $account->meta('city_id', $request->get('city_id'));
        $account->meta('country_id', $request->get('country_id'));
        $account->meta('area_id', $request->get('area_id'));
        $account->meta('payment_method', $request->get('payment_method'));
        $account->meta('shipper_id', $request->get('shipper_id'));

        //Create New Location
        $checkIfLocationExists = Location::where('account_id', $account->id)
            ->where('street', $request->get('address'))
            ->where('city_id', $request->get('city_id'))
            ->where('country_id', $request->get('country_id'))
            ->where('area_id', $request->get('area_id'))
            ->first();
        if(!$checkIfLocationExists){
            $location = new Location();
            $location->account_id = auth('accounts')->user()->id;
            $location->street = $request->get('address');
            $location->city_id = $request->get('city_id');
            $location->country_id = $request->get('country_id');
            $location->area_id = $request->get('area_id');
            $location->save();
        }

    }
}
