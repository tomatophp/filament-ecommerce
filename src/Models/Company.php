<?php

namespace TomatoPHP\FilamentEcommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use TomatoPHP\FilamentLocations\Models\Country;

/**
 * @property integer $id
 * @property integer $country_id
 * @property string $name
 * @property string $ceo
 * @property string $address
 * @property string $city
 * @property string $zip
 * @property string $registration_number
 * @property string $tax_number
 * @property string $email
 * @property string $phone
 * @property string $website
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 * @property Branch[] $branches
 * @property Country $country
 */
class Company extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var array
     */
    protected $fillable = ['country_id', 'name', 'ceo', 'address', 'city', 'zip', 'registration_number', 'tax_number', 'email', 'phone', 'website', 'notes', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branches()
    {
        return $this->hasMany('TomatoPHP\FilamentEcommerce\Models\Branch', 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
