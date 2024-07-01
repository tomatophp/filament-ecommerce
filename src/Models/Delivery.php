<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property boolean $is_activated
 * @property string $created_at
 * @property string $updated_at
 * @property Order[] $orders
 * @property ShippingPrice[] $shippingPrices
 */
class Delivery extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['shipping_vendor_id','name', 'phone', 'address', 'is_activated', 'created_at', 'updated_at'];

    protected $casts = [
        'is_activated' => 'boolean'
    ];

    public function vendor(){
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\ShippingVendor', 'shipping_vendor_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\Order', 'shipper_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippingPrices()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\ShippingPrice');
    }
}
