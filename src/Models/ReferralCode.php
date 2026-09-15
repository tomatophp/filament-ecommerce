<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $account_id
 * @property string $name
 * @property string $code
 * @property float $counter
 * @property bool $is_activated
 * @property bool $is_public
 * @property string $created_at
 * @property string $updated_at
 * @property Account $account
 */
class ReferralCode extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['team_id', 'account_id', 'name', 'code', 'counter', 'is_activated', 'is_public', 'created_at', 'updated_at'];

    protected $casts = [
        'is_activated' => 'boolean',
        'is_public' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(config('filament-accounts.model'));
    }
}
