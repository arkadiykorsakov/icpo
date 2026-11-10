<?php

namespace App\Models;

use App\Enums\LanguageLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpokenLanguage extends Model
{
    protected $fillable = [
        'criminal_id',
        'language_id',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    public function criminal(): BelongsTo
    {
        return $this->belongsTo(Criminal::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
