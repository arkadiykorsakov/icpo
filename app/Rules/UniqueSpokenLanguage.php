<?php

namespace App\Rules;

use App\Models\SpokenLanguage;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueSpokenLanguage implements ValidationRule
{
    public function __construct(
        protected mixed $criminalId
    ) {}

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        $exists = SpokenLanguage::query()
            ->where('criminal_id', $this->criminalId)
            ->where('language_id', $value)
            ->exists();

        if ($exists) {
            $fail('Такой язык уже имеется у преступника.');
        }
    }
}
