<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $code
 * @property string $type
 * @property float $amount
 * @property boolean $is_limited
 * @property string $end_at
 * @property integer $use_limit
 * @property integer $use_limit_by_user
 * @property integer $order_total_limit
 * @property boolean $is_activated
 * @property boolean $is_marketing
 * @property string $marketer_name
 * @property string $marketer_type
 * @property float $marketer_amount
 * @property float $marketer_amount_max
 * @property boolean $marketer_show_amount_max
 * @property boolean $marketer_hide_total_sales
 * @property float $is_used
 * @property mixed $apply_to
 * @property mixed $except
 * @property string $created_at
 * @property string $updated_at
 * @property Order[] $orders
 */
class Coupon extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['team_id', 'code', 'type', 'amount', 'is_limited', 'end_at', 'use_limit', 'use_limit_by_user', 'order_total_limit', 'is_activated', 'is_marketing', 'marketer_name', 'marketer_type', 'marketer_amount', 'marketer_amount_max', 'marketer_show_amount_max', 'marketer_hide_total_sales', 'is_used', 'apply_to', 'except', 'created_at', 'updated_at'];

    protected $casts = [
        "apply_to" => "json",
        "except" => "json",
        "is_activated" => "boolean",
        "is_marketing" => "boolean",
        "marketer_show_amount_max" => "boolean",
        "marketer_hide_total_sales" => "boolean",
        "is_limited" => "boolean",
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function discount(?float $total=null)
    {
        if($this->type === 'percentage_coupon'){
            return $total * $this->amount / 100;
        }
        else {
            return $this->amount;
        }
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        if(class_exists(TomatoPHP\TomatoOrders\Models\Order::class)){
            return $this->hasMany(TomatoPHP\TomatoOrders\Models\Order::class);
        }
    }
}
