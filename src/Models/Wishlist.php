<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use TomatoPHP\TomatoProducts\Models\Product;

/**
 * @property integer $id
 * @property integer $account_id
 * @property integer $product_id
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 * @property Product $product
 */
class Wishlist extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['account_id', 'product_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(config('tomato-crm.model'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
