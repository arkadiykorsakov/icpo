<?php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TrimmedNotEmpty implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (trim((string) $value) === '') {
            $fail("Поле {$attribute} не должно быть пустым или состоять из пробелов.");
        }
    }
}

