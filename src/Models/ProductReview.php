<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $account_id
 * @property float $rate
 * @property string $review
 * @property boolean $is_activated
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(config('filament-accounts.model'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\Product');
    }
}
