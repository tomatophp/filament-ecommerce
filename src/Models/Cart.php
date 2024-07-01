<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use TomatoPHP\TomatoProducts\Models\Product;

/**
 * @property integer $id
 * @property integer $account_id
 * @property integer $product_id
 * @property string $session_id
 * @property string $item
 * @property float $price
 * @property float $discount
 * @property float $vat
 * @property float $qty
 * @property float $total
 * @property string $note
 * @property mixed $options
 * @property boolean $is_active
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 * @property Product $product
 */
class Cart extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['account_id', 'product_id', 'session_id', 'item', 'price', 'discount', 'vat', 'qty', 'total', 'note', 'options', 'is_active', 'created_at', 'updated_at'];

    protected $casts = [
        "is_active" => "boolean",
        "options" => "json"
    ];
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
