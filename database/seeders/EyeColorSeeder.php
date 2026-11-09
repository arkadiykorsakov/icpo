<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EyeColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            // Карие
            'Карий',
            'Светло-карий',
            'Тёмно-карий',
            'Чёрный',
            'Янтарный',
            'Ореховый',

            // Зелёные
            'Зелёный',
            'Светло-зелёный',
            'Тёмно-зелёный',
            'Серо-зелёный',
            'Жёлто-зелёный',
            'Болотный',

            // Голубые и синие
            'Голубой',
            'Синий',
            'Серо-голубой',
            'Сине-зелёный',

            // Серые
            'Серый',
            'Светло-серый',
            'Тёмно-серый',

            // Особые
            'Разноцветные (гетерохромия)',
        ];

        DB::table('eye_colors')->insertOrIgnore(
            array_map(fn (string $name) => ['name' => $name], $colors)
        );
    }
}
