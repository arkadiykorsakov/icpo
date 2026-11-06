<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = json_decode(
            file_get_contents(database_path('data/languages.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach (array_chunk($languages, 500) as $chunk) {
            DB::table('languages')->insertOrIgnore($chunk);
        }
    }
}
