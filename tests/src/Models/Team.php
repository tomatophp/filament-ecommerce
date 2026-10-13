<?php

namespace TomatoPHP\FilamentEcommerce\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = ['name', 'user_id'];
}
