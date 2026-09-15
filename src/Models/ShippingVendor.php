<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property string $contact_person
 * @property string $phone
 * @property string $address
 * @property bool $is_activated
 * @property mixed $integration
 * @property string $created_at
 * @property string $updated_at
 * @property Order[] $orders
 * @property ShippingPrice[] $shippingPrices
 */
class ShippingVendor extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var array
     */
    protected $fillable = ['team_id', 'price', 'name', 'delivery_estimation', 'contact_person', 'phone', 'address', 'is_activated', 'integration', 'created_at', 'updated_at'];

    protected $casts = [
        'is_activated' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function deliveries()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\Delivery');
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\Order');
    }

    /**
     * @return HasMany
     */
    public function shippingPrices()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\ShippingPrice');
    }
}
