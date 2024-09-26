<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property integer $id
 * @property string $name
 * @property string $contact_person
 * @property string $phone
 * @property string $address
 * @property boolean $is_activated
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
    protected $fillable = ['team_id', 'price','name', 'delivery_estimation','contact_person', 'phone', 'address', 'is_activated', 'integration', 'created_at', 'updated_at'];

    protected $casts = [
        'is_activated' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function deliveries(){
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\Delivery');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\Order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippingPrices()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\ShippingPrice');
    }
}
