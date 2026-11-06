<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OffenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categories = json_decode(
            file_get_contents(database_path('data/offense_categories.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $rows = array_map(fn (array $c) => [
            'name'        => $c['name'],
            'description' => $c['description'],
            'created_at'  => $now,
        ], $categories);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('offense_categories')->insertOrIgnore(
                $chunk,
            );
        }
    }
}
