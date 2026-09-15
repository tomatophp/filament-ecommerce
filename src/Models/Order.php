<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use TomatoPHP\FilamentEcommerce\Database\Factories\OrderFactory;
use TomatoPHP\FilamentLocations\Models\Area;
use TomatoPHP\FilamentLocations\Models\City;
use TomatoPHP\FilamentLocations\Models\Country;
use TomatoPHP\FilamentLocations\Models\Location;

/**
 * @property int $id
 * @property int $user_id
 * @property int $country_id
 * @property int $area_id
 * @property int $city_id
 * @property int $address_id
 * @property int $account_id
 * @property int $cashier_id
 * @property int $coupon_id
 * @property int $shipper_id
 * @property int $shipping_vendor_id
 * @property int $branch_id
 * @property string $uuid
 * @property string $type
 * @property string $name
 * @property string $phone
 * @property string $flat
 * @property string $address
 * @property string $source
 * @property string $shipper_vendor
 * @property float $total
 * @property float $discount
 * @property float $shipping
 * @property float $vat
 * @property string $status
 * @property bool $is_approved
 * @property bool $is_closed
 * @property bool $is_on_table
 * @property string $table
 * @property string $notes
 * @property bool $has_returns
 * @property float $return_total
 * @property string $reason
 * @property bool $is_payed
 * @property string $payment_method
 * @property string $payment_vendor
 * @property string $payment_vendor_id
 * @property string $created_at
 * @property string $updated_at
 * @property Invoice[] $invoices
 * @property OrderLog[] $orderLogs
 * @property OrderMeta[] $orderMetas
 * @property Account $customer
 * @property Location $location
 * @property Area $area
 * @property Branch $branch
 * @property User $user
 * @property City $city
 * @property Country $country
 * @property Coupon $coupon
 * @property Delivery $delivery
 * @property ShippingVendor $shippingVendor
 * @property User $cashier
 * @property OrdersItem[] $ordersItems
 */
class Order extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'team_id',
        'company_id',
        'user_id',
        'country_id',
        'area_id',
        'city_id',
        'address_id',
        'account_id',
        'cashier_id',
        'coupon_id',
        'shipper_id',
        'shipping_vendor_id',
        'branch_id',
        'uuid',
        'type',
        'name',
        'phone',
        'flat',
        'address',
        'source',
        'shipper_vendor',
        'total',
        'discount',
        'shipping',
        'vat',
        'status',
        'is_approved',
        'is_closed',
        'is_on_table',
        'table',
        'notes',
        'has_returns',
        'return_total',
        'reason',
        'is_payed',
        'payment_method',
        'payment_vendor',
        'payment_vendor_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_approved' => 'bool',
        'is_closed' => 'bool',
        'is_on_table' => 'bool',
        'has_returns' => 'bool',
        'is_payed' => 'bool',
    ];

    /**
     * @return BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * @return HasMany
     */
    public function orderLogs()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\OrderLog');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);

    }

    /**
     * @return HasMany
     */
    public function orderMetas()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\OrderMeta');
    }

    /**
     * @param  string|null  $value
     * @return Model|string
     */
    public function meta(string $key, mixed $value = null): mixed
    {
        if ($value) {
            return $this->orderMetas()->updateOrCreate(['key' => $key], ['value' => $value]);
        } else {
            return $this->orderMetas()->where('key', $key)->firstOrCreate()?->value;
        }
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(config('filament-accounts.model'), 'account_id');
    }

    /**
     * @return BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'address_id');
    }

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
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return BelongsTo
     */
    public function cashier()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'cashier_id');
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
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * @return BelongsTo
     */
    public function shipper()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\Delivery', 'shipper_id');
    }

    /**
     * @return BelongsTo
     */
    public function shippingVendor()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\ShippingVendor');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * @return HasMany
     */
    public function ordersItems()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\OrdersItem');
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
