<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Country extends Model
{
    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'iso2', 'iso3'];

    public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(City::class);
    }

    // преступники, родившиеся в городах этой страны — напрямую
    public function birthCriminals(): HasManyThrough
    {
        return $this->hasManyThrough(
            Criminal::class,
            City::class,
            'country_id',
            'birth_city_id',
            'id',
            'id',
        );
    }
}
