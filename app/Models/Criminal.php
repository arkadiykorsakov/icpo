<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criminal extends Model
{
    use SoftDeletes;

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
        'death_date',
        'death_city_id',
        'is_archived'
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'has_head_hair' => 'boolean',
            'height_cm' => 'integer',
            'weight_kg' => 'decimal:1',
            'birth_date' => 'date',
            'death_date' => 'date',
            'is_archived' => 'boolean'
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Criminal $criminal): void {
            if (! $criminal->has_head_hair) {
                $criminal->hair_color_id = null;
            }

            if (! $criminal->birth_date) {
                $criminal->birth_city_id = null;
            }

            if (! $criminal->death_date) {
                $criminal->death_city_id = null;
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
        return $this->belongsTo(City::class, 'birth_city_id');
    }

    public function deathCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'death_city_id');
    }

    public function sightings(): HasMany
    {
        return $this->hasMany(CriminalSighting::class);
    }

    public function spokenLanguages(): HasMany
    {
        return $this->hasMany(SpokenLanguage::class);
    }

    public function citizenships(): HasMany
    {
        return $this->hasMany(Citizenship::class);
    }

    protected function fullName(): Attribute
    {
        return new Attribute(
            get: fn () => collect([
                $this->last_name,
                $this->first_name,
                $this->middle_name,
            ])->filter()->implode(' '),
        );
    }
}
