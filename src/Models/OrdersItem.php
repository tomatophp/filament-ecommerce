<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use TomatoPHP\TomatoProducts\Models\Product;

/**
 * @property integer $id
 * @property integer $order_id
 * @property integer $account_id
 * @property integer $product_id
 * @property integer $refund_id
 * @property integer $warehouse_move_id
 * @property string $item
 * @property float $price
 * @property float $discount
 * @property float $tax
 * @property float $total
 * @property float $returned
 * @property float $qty
 * @property float $returned_qty
 * @property boolean $is_free
 * @property boolean $is_returned
 * @property mixed $options
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 * @property Order $order
 * @property Product $product
 */
class OrdersItem extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['order_id', 'account_id', 'product_id', 'refund_id', 'warehouse_move_id', 'item', 'price', 'discount', 'vat', 'total', 'returned', 'qty', 'code', 'returned_qty', 'is_free', 'is_returned', 'options', 'created_at', 'updated_at'];

    protected $casts = [
        "options" => "json"
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
    public function order()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\Order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(\TomatoPHP\FilamentEcommerce\Models\Product::class);
    }
}
