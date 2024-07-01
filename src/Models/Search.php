<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $search
 * @property integer $count
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
