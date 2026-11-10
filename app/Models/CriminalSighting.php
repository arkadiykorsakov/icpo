<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriminalSighting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'criminal_id',
        'city_id',
        'description',
        'effective_date',
        'effective_time',
    ];

    public function criminal(): BelongsTo
    {
        return $this->belongsTo(Criminal::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
