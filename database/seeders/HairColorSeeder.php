<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HairColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            'Чёрный',
            'Тёмно-каштановый',
            'Каштановый',
            'Светло-каштановый',
            'Тёмно-русый',
            'Русый',
            'Светло-русый',
            'Пепельный',
            'Золотистый',
            'Блонд',
            'Платиновый блонд',
            'Рыжий',
            'Медный',
            'Седой',
            'Окрашенные (яркие оттенки)',
        ];

        DB::table('hair_colors')->insertOrIgnore(
            array_map(fn (string $name) => ['name' => $name], $colors)
        );
    }
}
