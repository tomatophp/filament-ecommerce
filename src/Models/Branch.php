<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use TomatoPHP\FilamentEcommerce\Database\Factories\BranchFactory;

/**
 * @property int $id
 * @property string $name
 * @property Company $company_id
 * @property int $branch_number
 * @property string $phone
 * @property string $address
 * @property string $created_at
 * @property string $updated_at
 * @property Invoice[] $invoices
 * @property Order[] $orders
 */
class Branch extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['company_id', 'branch_number', 'name', 'phone', 'address', 'created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo('TomatoPHP\FilamentEcommerce\Models\Company');
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected static function newFactory(): BranchFactory
    {
        return BranchFactory::new();
    }
}
