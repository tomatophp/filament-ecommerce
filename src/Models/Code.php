<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_id
 * @property string $code
 * @property bool $is_used
 * @property string $used_at
 * @property string $expires_at
 * @property Product $product
 */
class Code extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'code',
        'is_used',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
