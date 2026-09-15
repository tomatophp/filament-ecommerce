<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property int $account_id
 * @property float $rate
 * @property string $review
 * @property bool $is_activated
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 * @property Product $product
 */
class ProductReview extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['product_id', 'account_id', 'rate', 'review', 'is_activated', 'created_at', 'updated_at'];

    protected $casts = [
        'is_activated' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(config('filament-accounts.model'));
    }

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\Product');
    }
}
