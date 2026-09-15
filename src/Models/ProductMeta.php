<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property string $key
 * @property mixed $value
 * @property int $model_id
 * @property string $model_type
 * @property string $created_at
 * @property string $updated_at
 * @property Product $product
 */
class ProductMeta extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['product_id', 'key', 'value', 'model_id', 'model_type', 'created_at', 'updated_at'];

    protected $casts = [
        'value' => 'json',
    ];

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\Product');
    }
}
