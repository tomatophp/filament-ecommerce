<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use TomatoPHP\FilamentLocations\Models\Area;
use TomatoPHP\FilamentLocations\Models\City;
use TomatoPHP\FilamentLocations\Models\Country;

/**
 * @property int $id
 * @property int $shipping_vendor_id
 * @property int $delivery_id
 * @property int $country_id
 * @property int $city_id
 * @property int $area_id
 * @property string $type
 * @property float $price
 * @property string $created_at
 * @property string $updated_at
 * @property Area $area
 * @property City $city
 * @property Country $country
 * @property Delivery $delivery
 * @property ShippingVendor $shippingVendor
 */
class ShippingPrice extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['shipping_vendor_id', 'delivery_id', 'country_id', 'city_id', 'area_id', 'type', 'price', 'created_at', 'updated_at'];

    /**
     * @return BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function delivery()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\Delivery');
    }

    /**
     * @return BelongsTo
     */
    public function shippingVendor()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\ShippingVendor');
    }
}
