<?php

namespace App\Rules;

use App\Models\City;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCity implements ValidationRule
{
    public function __construct(
        protected mixed $countryId
    ) {}

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        $exists = City::query()
            ->whereRaw('LOWER(name) = LOWER(?)', [$value])
            ->where('country_id', $this->countryId)
            ->exists();

        if ($exists) {
            $fail('Город с таким названием уже существует в этой стране.');
        }
    }
}
