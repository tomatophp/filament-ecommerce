<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $search
 * @property int $count
 * @property string $created_at
 * @property string $updated_at
 */
class Search extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['search', 'count', 'created_at', 'updated_at'];
}
