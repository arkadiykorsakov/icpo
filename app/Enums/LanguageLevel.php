<?php

namespace App\Enums;

enum LanguageLevel: string
{
    case A1 = 'A1';
    case A2 = 'A2';
    case B1 = 'B1';
    case B2 = 'B2';
    case C1 = 'C1';
    case C2 = 'C2';
    case NATIVE = 'Native';

    public function label(): string
    {
        return match ($this) {
            self::A1 => 'A1 — Beginner',
            self::A2 => 'A2 — Elementary',
            self::B1 => 'B1 — Intermediate',
            self::B2 => 'B2 — Upper Intermediate',
            self::C1 => 'C1 — Advanced',
            self::C2 => 'C2 — Proficiency',
            self::NATIVE => 'Родной язык (носитель)',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $level) => [
                $level->value => $level->label(),
            ])
            ->all();
    }
}
