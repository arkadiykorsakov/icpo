<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Criminal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'nickname',
        'gender',
        'height_cm',
        'weight_kg',
        'has_head_hair',
        'hair_color_id',
        'eye_color_id',
        'birth_date',
        'birth_city_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'has_head_hair' => 'boolean',
            'height_cm' => 'integer',
            'weight_kg' => 'decimal:1',
            'birth_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Criminal $criminal) {
            if (! $criminal->has_head_hair) {
                $criminal->hair_color_id = null;
            }
        });
    }

    public function hairColor(): BelongsTo
    {
        return $this->belongsTo(HairColor::class);
    }

    public function eyeColor(): BelongsTo
    {
        return $this->belongsTo(EyeColor::class);
    }

    public function birthCity(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
