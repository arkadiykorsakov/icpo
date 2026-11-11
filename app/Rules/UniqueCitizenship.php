<?php

namespace App\Rules;

use App\Models\Citizenship;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCitizenship implements ValidationRule
{
    public function __construct(
        protected mixed $criminalId
    ) {}

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        $exists = Citizenship::query()
            ->where('criminal_id', $this->criminalId)
            ->where('country_id', $value)
            ->exists();

        if ($exists) {
            $fail('Такое гражданство уже имеется у преступника.');
        }
    }
}
