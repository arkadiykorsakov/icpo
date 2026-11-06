<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $countries = DB::table('countries')->pluck('id', 'iso2');

        $cities = json_decode(
            file_get_contents(database_path('data/cities.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $rows = [];

        foreach ($cities as $iso2 => $names) {
            $countryId = $countries[$iso2] ?? null;

            if ($countryId === null) {
                $this->command?->warn("Страна {$iso2} не найдена, пропускаю");
                continue;
            }

            foreach ($names as $name) {
                $rows[] = [
                    'name'       => $name,
                    'country_id' => $countryId,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('cities')->insertOrIgnore($chunk);
        }
    }
}
