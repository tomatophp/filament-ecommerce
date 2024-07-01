<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $order_id
 * @property string $status
 * @property string $note
 * @property boolean $is_closed
 * @property string $created_at
 * @property string $updated_at
 * @property Order $order
 * @property User $user
 */
class OrderLog extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'order_id', 'status', 'note', 'is_closed', 'created_at', 'updated_at'];

    protected $casts = [
        'is_closed' => 'boolean',
    ];
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
