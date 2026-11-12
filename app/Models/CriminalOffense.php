<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriminalOffense extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'criminal_id',
        'offense_category_id',
        'reason',
        'effective_date'
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date'
        ];
    }

    public function criminal(): BelongsTo
    {
        return $this->belongsTo(Criminal::class);
    }

    public function offenseCategory(): BelongsTo
    {
        return $this->belongsTo(OffenseCategory::class);
    }
}
