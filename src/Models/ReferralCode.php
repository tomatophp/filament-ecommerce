<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $account_id
 * @property string $name
 * @property string $code
 * @property float $counter
 * @property boolean $is_activated
 * @property boolean $is_public
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 */
class ReferralCode extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['account_id', 'name', 'code', 'counter', 'is_activated', 'is_public', 'created_at', 'updated_at'];

    protected $casts = [
        "is_activated" => "boolean",
        "is_public" => "boolean",
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(config('tomato-crm.model'));
    }
}
